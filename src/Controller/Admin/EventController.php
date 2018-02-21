<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventController
 */
class EventController extends Controller
{
    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * EventController constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/event", name="event_index")
     * @return Response
     */
    public function index()
    {
        /** @var Event[] $events */
        $events = $this->repository->findAll();

        return $this->render(
            'Admin/Event/index.html.twig',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * @Route("/event/create", name="event_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/event/{eventId}", name="event_show")
     * @param $eventId
     * @return Response
     * @throws \Exception
     */
    public function show($eventId)
    {
        /** @var Event $event */
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        return $this->render(
            'Admin/Event/show.html.twig',
            [
                'event' => $event,
            ]
        );
    }

    /**
     * @Route("/event/{eventId}/update", name="event_update")
     * @param Request $request
     * @param         $eventId
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function update(Request $request, $eventId)
    {
        /** @var Event $event */
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        $form = $this->createForm(EventType::class, $event);
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
     * @Route("/event/{eventId}/save_order", name="event_save_category_order")
     * @param Request $request
     * @param         $eventId
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveCategoryOrder(Request $request, $eventId)
    {
        /** @var Event $event */
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
                'message' => sprintf('Event by id %s not found', $eventId),
            ])->setStatusCode(400);
        }

        return $response;
    }
}
