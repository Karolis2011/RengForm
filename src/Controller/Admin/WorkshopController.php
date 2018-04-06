<?php

namespace App\Controller\Admin;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\CategoryRepository;
use App\Repository\MultiEventRepository;
use App\Repository\WorkshopRepository;
use App\Repository\WorkshopTimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WorkshopController
 */
class WorkshopController extends Controller
{
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
     * WorkshopController constructor.
     * @param WorkshopRepository     $repository
     * @param CategoryRepository     $categoryRepository
     * @param WorkshopTimeRepository $workshopTimeRepository
     */
    public function __construct(
        WorkshopRepository $repository,
        CategoryRepository $categoryRepository,
        WorkshopTimeRepository $workshopTimeRepository
    ) {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
        $this->workshopTimeRepository = $workshopTimeRepository;
    }

    /**
     * @param                      $eventId
     * @param Request              $request
     * @param MultiEventRepository $eventRepository
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create($eventId, Request $request, MultiEventRepository $eventRepository)
    {
        $event = $eventRepository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('MultiEvent by id %s not found', $eventId));
        }

        $workshop = new Workshop();
        $form = $this->createForm(
            WorkshopType::class,
            $workshop,
            [
                'eventId' => $eventId,
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
                'form'   => $form->createView(),
                'event'  => $event,
            ]
        );
    }

    /**
     * @param Request $request
     * @param         $workshopId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Request $request, $workshopId)
    {
        $workshop = $this->repository->find($workshopId);

        if ($workshop === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopId));
        }

        $form = $this->createForm(
            WorkshopType::class,
            $workshop,
            [
                'eventId' => $workshop->getCategory()->getEvent()->getId(),
            ]
        );

        $originalTimes = new ArrayCollection();
        foreach ($workshop->getTimes() as $time) {
            $originalTimes->add($time);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTimes as $time) {
                if (false === $workshop->getTimes()->contains($time)) {
                    $this->workshopTimeRepository->remove($time);
                }
            }

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
}
