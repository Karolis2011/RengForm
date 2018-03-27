<?php

namespace App\Controller\Event;

use App\Entity\MultiEvent;
use App\Repository\MultiEventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventController
 */
class EventController extends Controller
{
    /**
     * @var MultiEventRepository
     */
    private $repository;

    /**
     * EventController constructor.
     * @param MultiEventRepository $repository
     */
    public function __construct(MultiEventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/event/{eventId}", name="event")
     * @param $eventId
     * @return Response
     * @throws \Exception
     */
    public function index($eventId)
    {
        /** @var MultiEvent $event */
        $event = $this->repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('MultiEvent by id %s not found', $eventId));
        }

        return $this->render(
            'Default/event.html.twig',
            [
                'event' => $event,
            ]
        );
    }
}
