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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     */
    public function create(Request $request, $eventId)
    {
        $event = $this->eventRepository->find($eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
            //TODO: Log
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
     */
    public function edit(Request $request, $categoryId)
    {
        $category = $this->repository->find($categoryId);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category by id %s not found', $categoryId));
            //TODO: Log
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

    /**
     * @param $categoryId
     * @return RedirectResponse
     */
    public function delete($categoryId)
    {
        $category = $this->repository->find($categoryId);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category by id %s not found', $categoryId));
            //TODO: Log
        }

        $eventId = $category->getEvent()->getId();

        $this->repository->remove($category);
        $this->addFlash('success', "Category successfully deleted");

        return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
    }
}
