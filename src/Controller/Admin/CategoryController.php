<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\MultiEventRepository;
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
     * @var CategoryRepository
     */
    private $repository;

    /**
     * @var MultiEventRepository
     */
    private $eventRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository   $repository
     * @param MultiEventRepository $eventRepository
     */
    public function __construct(CategoryRepository $repository, MultiEventRepository $eventRepository)
    {
        $this->repository = $repository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param Request $request
     * @param         $eventId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request, $eventId)
    {
        $event = $this->eventRepository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('MultiEvent by id %s not found', $eventId));
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setEvent($event);
            $this->repository->save($category);

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
     * @param Request $request
     * @param         $categoryId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Request $request, $categoryId)
    {
        $category = $this->repository->find($categoryId);

        if ($category === null) {
            throw new \Exception(sprintf('Category by id %s not found', $categoryId));
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->update($category);

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
}
