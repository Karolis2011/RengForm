<?php

namespace App\Controller;

use App\Entity\Category;
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
     * @Route("/category/{categoryId}/workshop/create", name="workshop_create")
     * @param Request $request
     * @param         $categoryId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request, $categoryId)
    {
        /** @var Category $category */
        $category = $this->getCategoryRepository()->find($categoryId);

        if ($category === null) {
            throw new \Exception(sprintf('Category by id %s not found', $categoryId));
        }

        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshop->setCategory($category);
            $this->getRepository()->save($workshop);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $category->getEvent()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Workshop/create.html.twig',
            [
                'form' => $form->createView(),
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
        $workshop = $this->getRepository()->find($workshopId);

        if ($workshop === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopId));
        }

        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getRepository()->update($workshop);

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
            ]
        );
    }

    /**
     * @return CategoryRepository
     */
    private function getCategoryRepository(): CategoryRepository
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);

        return $repository;
    }

    /**
     * @return WorkshopRepository
     */
    private function getRepository(): WorkshopRepository
    {
        $repository = $this->getDoctrine()->getRepository(Workshop::class);

        return $repository;
    }
}
