<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EventController
 */
class EventController extends Controller
{
    /**
     * @Route("/event", name="event_index")
     * @return Response
     */
    public function index()
    {
        /** @var Event[] $events */
        $events = $this->getRepository()->findAll();

        return $this->render(
            'Event/index.html.twig',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * @Route("/event/create", name="event_create")
     * @return Response
     */
    public function create()
    {
        return new Response('create event');
    }

    /**
     * @Route("/event/{eventId}", name="event_show")
     * @param $eventId
     * @return Response
     */
    public function show($eventId)
    {
        /** @var Event $event */
        $event = $this->getRepository()->find($eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
        }

        return $this->render(
            'Event/show.html.twig',
            [
                'event' => $event,
            ]
        );
    }

    /**
     * @Route("/event/{eventId}/update", name="event_update")
     * @param $eventId
     * @return Response
     */
    public function update($eventId)
    {
        return new Response('update event id: ' . $eventId);
    }

    /**
     * @return EventRepository
     */
    private function getRepository(): EventRepository
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);

        return $repository;
    }
}
