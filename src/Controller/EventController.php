<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @param $eventId
     * @return Response
     * @throws \Exception
     */
    public function index($eventId)
    {
        /** @var Event $event */
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        return $this->render(
            'Default/event.html.twig',
            [
                'event' => $event,
            ]
        );
    }
}
