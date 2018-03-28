<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\MultiEvent;
use App\Form\MultiEventType;
use App\Repository\MultiEventRepository;
use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MultiEventController
 */
class MultiEventController extends Controller
{
    /**
     * @var MultiEventRepository
     */
    private $repository;

    /**
     * @var WorkshopRepository
     */
    private $workshopRepository;

    /**
     * EventController constructor.
     * @param MultiEventRepository $repository
     * @param WorkshopRepository   $workshopRepository
     */
    public function __construct(MultiEventRepository $repository, WorkshopRepository $workshopRepository)
    {
        $this->repository = $repository;
        $this->workshopRepository = $workshopRepository;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $event = new MultiEvent();
        $form = $this->createForm(MultiEventType::class, $event);
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
            'Admin/MultiEvent/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param         $eventId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function update(Request $request, $eventId)
    {
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('MultiEvent by id %s not found', $eventId));
        }

        $form = $this->createForm(MultiEventType::class, $event);
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
            'Admin/MultiEvent/update.html.twig',
            [
                'form'  => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * @param Request $request
     * @param         $eventId
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveCategoryOrder(Request $request, $eventId)
    {
        $event = $this->repository->find($eventId);
        $response = new JsonResponse(null, 200);

        if ($event !== null) {
            $order = $request->get('order');

            if (!empty($order) && count($order) == count($event->getCategories())) {
                $order = array_flip($order);

                /** @var Category $category */
                foreach ($event->getCategories() as $category) {
                    $orderNo = $order[$category->getId()];
                    $category->setOrderNo($orderNo);
                }

                $this->getDoctrine()->getManager()->flush();
            } else {
                $response->setData([
                    'message' => 'Bad number of ordered categories',
                ])->setStatusCode(400);
            }
        } else {
            $response->setData([
                'message' => sprintf('MultiEvent by id %s not found', $eventId),
            ])->setStatusCode(400);
        }

        return $response;
    }
}
