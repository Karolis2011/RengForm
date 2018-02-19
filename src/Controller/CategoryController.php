<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Event;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 */
class CategoryController extends Controller
{
    /**
     * @Route("/event/{eventId}/category/create", name="category_create")
     * @param Request $request
     * @param         $eventId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request, $eventId)
    {
        /** @var Event $event */
        $event = $this->getEventRepository()->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setEvent($event);
            $this->getRepository()->save($category);
            $category->setOrderNo($category->getId());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $category->getEvent()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Category/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/category/{categoryId}/update", name="category_update")
     * @param Request $request
     * @param         $categoryId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Request $request, $categoryId)
    {
        /** @var Category $category */
        $category = $this->getRepository()->find($categoryId);

        if ($category === null) {
            throw new \Exception(sprintf('Category by id %s not found', $categoryId));
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getRepository()->update($category);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $category->getEvent()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Category/update.html.twig',
            [
                'form'     => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * @return EventRepository
     */
    private function getEventRepository(): EventRepository
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);

        return $repository;
    }

    /**
     * @return CategoryRepository
     */
    private function getRepository(): CategoryRepository
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);

        return $repository;
    }
}
