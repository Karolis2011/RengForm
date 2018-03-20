<?php

namespace App\Controller\Admin;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\CategoryRepository;
use App\Repository\WorkshopRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * WorkshopController constructor.
     * @param WorkshopRepository $repository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(WorkshopRepository $repository, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/event/{eventId}/workshop/create", name="workshop_create")
     * @param         $eventId
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create($eventId, Request $request)
    {
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
                    'eventId' => $workshop->getCategory()->getEvent()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Workshop/create.html.twig',
            [
                'form'   => $form->createView(),
                'errors' => $form->getErrors(true),
            ]
        );
    }

    /**
     * @Route("/workshop/{workshopId}/update", name="workshop_update")
     * @param Request $request
     * @param         $workshopId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Request $request, $workshopId)
    {
        /** @var Workshop $workshop */
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
                'errors'   => $form->getErrors(),
            ]
        );
    }
}
