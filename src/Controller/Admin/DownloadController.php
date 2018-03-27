<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\MultiEventRepository;
use App\Repository\WorkshopTimeRepository;
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
     * @Route("/download/event_multi/{eventId}", name="download_registrations_event_multi")
     * @param                 $eventId
     * @param MultiEventRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function multiEvent($eventId, MultiEventRepository $repository)
    {
        $event = $repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('MultiEvent by id %s not found', $eventId));
        }

        return $this->exporter->export($event);
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
        $event = $repository->find($eventId);

        if ($event === null) {
            throw new \Exception(sprintf('Event by id %s not found', $eventId));
        }

        return $this->exporter->export($event);
    }

    /**
     * @Route("/download/workshop", name="download_registrations_workshop")
     * @param Request            $request
     * @param WorkshopTimeRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function workshop(Request $request, WorkshopTimeRepository $repository)
    {
        $workshopTimeId = $request->get('workshop');
        $workshopTime = $repository->find($workshopTimeId);

        if ($workshopTime === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopTimeId));
        }

        return $this->exporter->export($workshopTime);
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
