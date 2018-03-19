<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\Export\Exporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DownloadController
 */
class DownloadController extends Controller
{
    /**
     * @Route("/download/event/{eventId}", name="download_registrations_event")
     * @param                 $eventId
     * @param EventRepository $repository
     * @param Exporter        $exporter
     * @return Response
     * @throws \Exception
     */
    public function event($eventId, EventRepository $repository, Exporter $exporter)
    {
        /** @var Event $event */
        $event = $repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        return $exporter->export($event);
    }
}
