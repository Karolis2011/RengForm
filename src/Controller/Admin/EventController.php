<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\MultiEvent;
use App\Form\EventCreateType;
use App\Form\EventTimeModelCollectionType;
use App\Form\EventUpdateType;
use App\Form\Model\EventTimeModel;
use App\Repository\EventRepository;
use App\Repository\MultiEventRepository;
use App\Repository\WorkshopRepository;
use App\Service\Event\EventTimeUpdater;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EventController
 */
class EventController extends Controller
{
    /**
     * @var MultiEventRepository
     */
    private $multiEventRepository;

    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * @var WorkshopRepository
     */
    private $workshopRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EventController constructor.
     * @param MultiEventRepository $multiEventRepository
     * @param EventRepository      $repository
     * @param WorkshopRepository   $workshopRepository
     * @param LoggerInterface      $logger
     */
    public function __construct(
        MultiEventRepository $multiEventRepository,
        EventRepository $repository,
        WorkshopRepository $workshopRepository,
        LoggerInterface $logger
    ) {
        $this->multiEventRepository = $multiEventRepository;
        $this->repository = $repository;
        $this->workshopRepository = $workshopRepository;
        $this->logger = $logger;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $multiEvents = $this->multiEventRepository->findBy(['owner' => $user]);
        $events = $this->repository->findBy(['owner' => $user]);

        return $this->render(
            'Admin/Event/index.html.twig',
            [
                'multiEvents' => $multiEvents,
                'events'      => $events,
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $user = $this->getUser();
        $userId = null;
        if ($user !== null) {
            $userId = $user->getId();
        }
        $event = new Event();
        $form = $this->createForm(
            EventCreateType::class,
            $event,
            [
                'ownerId' => $userId,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setOwner($this->getUser());
            $this->repository->save($event);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $event->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Event/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param         $eventId
     * @return RedirectResponse|Response
     */
    public function update(Request $request, $eventId)
    {
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
        }

        $user = $this->getUser();
        $userId = null;
        if ($user !== null) {
            $userId = $user->getId();
        }

        $form = $this->createForm(
            EventUpdateType::class,
            $event,
            [
                'ownerId' => $userId,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->update($event);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $event->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Event/update.html.twig',
            [
                'form'  => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * @param Request          $request
     * @param EventTimeUpdater $updater
     * @param                  $eventId
     * @return RedirectResponse|Response
     */
    public function updateTimes(Request $request, EventTimeUpdater $updater, $eventId)
    {
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
        }

        $times = [];
        foreach ($event->getTimes() as $time) {
            $times[] = new EventTimeModel($time);
        }

        $form = $this->createForm(EventTimeModelCollectionType::class, ['times' => $times]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formTimes = $form->getData()['times'] ?? [];
            try {
                $updater->update($formTimes, $event);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Update error');
                $this->logger->error(
                    'Times update error',
                    [
                        'id'   => $eventId,
                        'type' => 'event',
                    ]
                );

                return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
            }

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $event->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Event/update_times.html.twig',
            [
                'event' => $event,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @param $eventId
     * @return Response
     */
    public function show($eventId)
    {
        $event = $this->multiEventRepository->find($eventId);

        if ($event !== null) {
            return $this->showMultiEvent($event);
        }

        $event = $this->repository->find($eventId);

        if ($event !== null) {
            return $this->showEvent($event);
        }

        throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
    }

    /**
     * @param $eventId
     * @return RedirectResponse
     */
    public function delete($eventId)
    {
        $event = $this->multiEventRepository->find($eventId);

        if ($event !== null) {
            $this->multiEventRepository->remove($event);
            $this->addFlash('success', "Event successfully deleted");

            return $this->redirectToRoute('event_index');
        }

        $event = $this->repository->find($eventId);

        if ($event !== null) {
            $this->repository->remove($event);
            $this->addFlash('success', "Event successfully deleted");

            return $this->redirectToRoute('event_index');
        }

        throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
    }

    /**
     * @param MultiEvent $event
     * @return Response
     */
    private function showMultiEvent(MultiEvent $event): Response
    {
        $workshops = $this->workshopRepository->getByEventId($event->getId());

        return $this->render(
            'Admin/MultiEvent/show.html.twig',
            [
                'event'     => $event,
                'workshops' => $workshops,
            ]
        );
    }

    /**
     * @param Event $event
     * @return Response
     */
    private function showEvent(Event $event): Response
    {
        return $this->render(
            'Admin/Event/show.html.twig',
            [
                'event' => $event,
            ]
        );
    }
}
