<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Entity\MultiEvent;
use App\Repository\EventRepository;
use App\Repository\MultiEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EventController
 */
class EventController extends Controller
{
    /**
     * @var MultiEventRepository
     */
    private $multiEventRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * EventController constructor.
     * @param MultiEventRepository $multiEventRepository
     * @param EventRepository $eventRepository
     */
    public function __construct(
        MultiEventRepository $multiEventRepository,
        EventRepository $eventRepository
    ) {
        $this->multiEventRepository = $multiEventRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param $eventId
     * @return Response
     */
    public function index($eventId)
    {
        $event = $this->multiEventRepository->find($eventId);

        if ($event !== null) {
            return $this->showMultiEvent($event);
        }

        $event = $this->eventRepository->find($eventId);

        if ($event !== null) {
            return $this->showEvent($event);
        }

        throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
    }

    /**
     * @param MultiEvent $event
     * @return Response
     */
    private function showMultiEvent(MultiEvent $event): Response
    {
        return $this->render(
            'Default\multiEvent.html.twig',
            [
                'event' => $event,
            ]
        );
    }

    /**
     * @param Event $event
     * @return Response
     */
    private function showEvent(Event $event): Response
    {
        return $this->render(
            'Default\events.html.twig',
            [
                'event' => $event,
            ]
        );
    }
}
