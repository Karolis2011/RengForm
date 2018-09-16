<?php

namespace App\Controller\Admin;

use App\Entity\MultiEvent;
use App\Entity\Workshop;
use App\Form\EventTimeModelCollectionType;
use App\Form\Model\EventTimeModel;
use App\Form\WorkshopCreateType;
use App\Form\WorkshopUpdateType;
use App\Repository\CategoryRepository;
use App\Repository\MultiEventRepository;
use App\Repository\WorkshopRepository;
use App\Repository\WorkshopTimeRepository;
use App\Service\Event\EventTimeUpdater;
use App\Service\Helper\SharedAmongUsersTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WorkshopController
 */
class WorkshopController extends Controller
{
    use SharedAmongUsersTrait;

    /**
     * @var WorkshopRepository
     */
    private $repository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var WorkshopTimeRepository
     */
    private $workshopTimeRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * WorkshopController constructor.
     * @param WorkshopRepository     $repository
     * @param CategoryRepository     $categoryRepository
     * @param WorkshopTimeRepository $workshopTimeRepository
     * @param LoggerInterface        $logger
     */
    public function __construct(
        WorkshopRepository $repository,
        CategoryRepository $categoryRepository,
        WorkshopTimeRepository $workshopTimeRepository,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
        $this->workshopTimeRepository = $workshopTimeRepository;
        $this->logger = $logger;
    }

    /**
     * @param Request              $request
     * @param MultiEventRepository $eventRepository
     * @param                      $eventId
     * @return RedirectResponse|Response
     */
    public function create(Request $request, MultiEventRepository $eventRepository, $eventId)
    {
        /** @var MultiEvent|null $event */
        $event = $this->findEntity($eventRepository, $eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
        }

        $user = $this->getUser();
        $userId = null;
        if ($user !== null) {
            $userId = $user->getId();
        }

        $workshop = new Workshop();
        $form = $this->createForm(
            WorkshopCreateType::class,
            $workshop,
            [
                'eventId'       => $eventId,
                'ownerId'       => $userId,
                'shared_events' => $this->getParameter('shared_events'),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($workshop);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $eventId,
                ]
            );
        }

        return $this->render(
            'Admin/Workshop/create.html.twig',
            [
                'form'  => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * @param Request $request
     * @param         $workshopId
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, $workshopId)
    {
        $workshop = $this->getWorkshop($workshopId);

        if ($workshop === null) {
            throw new NotFoundHttpException(sprintf('Workshop by id %s not found', $workshopId));
        }

        $user = $this->getUser();
        $userId = null;
        if ($user !== null) {
            $userId = $user->getId();
        }

        $form = $this->createForm(
            WorkshopUpdateType::class,
            $workshop,
            [
                'eventId'       => $workshop->getCategory()->getEvent()->getId(),
                'ownerId'       => $userId,
                'shared_events' => $this->getParameter('shared_events'),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->update($workshop);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $workshop->getCategory()->getEvent()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Workshop/update.html.twig',
            [
                'form'     => $form->createView(),
                'workshop' => $workshop,
                'event'    => $workshop->getCategory()->getEvent(),
            ]
        );
    }

    /**
     * @param Request          $request
     * @param EventTimeUpdater $updater
     * @param                  $workshopId
     * @return RedirectResponse|Response
     */
    public function updateTimes(Request $request, EventTimeUpdater $updater, $workshopId)
    {
        $workshop = $this->getWorkshop($workshopId);

        if ($workshop === null) {
            throw new NotFoundHttpException(sprintf('Workshop by id %s not found', $workshopId));
        }

        $times = [];
        foreach ($workshop->getTimes() as $time) {
            $times[] = new EventTimeModel($time);
        }

        $form = $this->createForm(EventTimeModelCollectionType::class, ['times' => $times]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formTimes = $form->getData()['times'] ?? [];
            try {
                $updater->update($formTimes, $workshop);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Update error');
                $this->logger->error(
                    'Times update error',
                    [
                        'id'   => $workshopId,
                        'type' => 'workshop',
                    ]
                );

                return $this->redirectToRoute('event_index');
            }

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $workshop->getCategory()->getEvent()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Workshop/update_times.html.twig',
            [
                'workshop' => $workshop,
                'event'    => $workshop->getCategory()->getEvent(),
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @param $workshopId
     * @return RedirectResponse
     */
    public function delete($workshopId)
    {
        $workshop = $this->getWorkshop($workshopId);

        if ($workshop === null) {
            throw new NotFoundHttpException(sprintf('Workshop by id %s not found', $workshopId));
        }

        $eventId = $workshop->getCategory()->getEvent()->getId();
        $this->repository->remove($workshop);
        $this->addFlash('success', "Workshop successfully deleted");

        return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
    }

    /**
     * @param $workshopId
     * @return Workshop|null
     */
    private function getWorkshop($workshopId): ?Workshop
    {
        $workshop = $this->repository->find($workshopId);

        if ($workshop !== null && !$this->isOwner($workshop->getCategory()->getEvent()->getOwner())) {
            return null;
        }

        return $workshop;
    }
}
