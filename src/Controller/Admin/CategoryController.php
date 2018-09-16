<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\MultiEvent;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\MultiEventRepository;
use App\Service\Helper\SharedAmongUsersTrait;
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
    use SharedAmongUsersTrait;

    /**
     * @var CategoryRepository
     */
    private $repository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository   $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
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

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setEvent($event);
            $this->repository->save($category);

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $eventId,
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
        $category = $this->getCategory($categoryId);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category by id %s not found', $categoryId));
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
        $category = $this->getCategory($categoryId);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category by id %s not found', $categoryId));
        }

        $eventId = $category->getEvent()->getId();

        $this->repository->remove($category);
        $this->addFlash('success', "Category successfully deleted");

        return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
    }

    /**
     * @param $categoryId
     * @return Category|null
     */
    private function getCategory($categoryId): ?Category
    {
        $category = $this->repository->find($categoryId);

        if ($category !== null && !$this->isOwner($category->getEvent()->getOwner())) {
            return null;
        }

        return $category;
    }
}
