<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\WorkshopRepository;
use App\Service\Export\Exporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DownloadController
 */
class DownloadController extends Controller
{
    /**
     * @var Exporter
     */
    private $exporter;

    /**
     * DownloadController constructor.
     * @param Exporter $exporter
     */
    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    /**
     * @Route("/download/event/{eventId}", name="download_registrations_event")
     * @param                 $eventId
     * @param EventRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function event($eventId, EventRepository $repository)
    {
        /** @var Event $event */
        $event = $repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        return $this->exporter->export($event);
    }

    /**
     * @Route("/download/workshop", name="download_registrations_workshop")
     * @param Request            $request
     * @param WorkshopRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function workshop(Request $request, WorkshopRepository $repository)
    {
        $workshopId = $request->get('workshop');
        $workshop = $repository->find($workshopId);

        if ($workshop === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopId));
        }

        return $this->exporter->export($workshop);
    }

    /**
     * @Route("/download/category", name="download_registrations_category")
     * @param Request            $request
     * @param CategoryRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function category(Request $request, CategoryRepository $repository)
    {
        $categoryId = $request->get('category');
        $category = $repository->find($categoryId);

        if ($category === null) {
            throw new \Exception(sprintf('Category by id %s not found', $categoryId));
        }

        return $this->exporter->export($category);
    }
}
