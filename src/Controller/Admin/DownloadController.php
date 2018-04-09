<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\MultiEventRepository;
use App\Repository\WorkshopTimeRepository;
use App\Service\Export\Exporter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DownloadController constructor.
     * @param Exporter        $exporter
     * @param LoggerInterface $logger
     */
    public function __construct(Exporter $exporter, LoggerInterface $logger)
    {
        $this->exporter = $exporter;
        $this->logger = $logger;
    }

    /**
     * @param MultiEventRepository $repository
     * @param                      $eventId
     * @return RedirectResponse|Response
     */
    public function multiEvent(MultiEventRepository $repository, $eventId)
    {
        $event = $repository->find($eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
        }

        try {
            return $this->exporter->export($event);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Export error');
            $this->log($eventId, 'multiEvent');

            return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
        }
    }

    /**
     * @param EventRepository $repository
     * @param                 $eventId
     * @return RedirectResponse|Response
     */
    public function event(EventRepository $repository, $eventId)
    {
        $event = $repository->find($eventId);

        if ($event === null) {
            throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
        }

        try {
            return $this->exporter->export($event);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Export error');
            $this->log($eventId, 'event');

            return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
        }
    }

    /**
     * @param WorkshopTimeRepository $repository
     * @param Request                $request
     * @return RedirectResponse|Response
     */
    public function workshop(WorkshopTimeRepository $repository, Request $request)
    {
        $workshopTimeId = $request->get('workshop');
        $workshopTime = $repository->find($workshopTimeId);

        if ($workshopTime === null) {
            throw new NotFoundHttpException(sprintf('Workshop by id %s not found', $workshopTimeId));
        }

        try {
            return $this->exporter->export($workshopTime);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Export error');
            $this->log($workshopTimeId, 'workshopTime');

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $workshopTime->getWorkshop()->getCategory()->getEvent()->getId(),
                ]
            );
        }
    }

    /**
     * @param CategoryRepository $repository
     * @param Request            $request
     * @return RedirectResponse|Response
     */
    public function category(CategoryRepository $repository, Request $request)
    {
        $categoryId = $request->get('category');
        $category = $repository->find($categoryId);

        if ($category === null) {
            throw new NotFoundHttpException(sprintf('Category by id %s not found', $categoryId));
        }

        try {
            return $this->exporter->export($category);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Export error');
            $this->log($categoryId, 'category');

            return $this->redirectToRoute(
                'event_show',
                [
                    'eventId' => $category->getEvent()->getId(),
                ]
            );
        }
    }

    /**
     * @param string $objectId
     * @param string $type
     */
    private function log(string $objectId, string $type): void
    {
        $this->logger->error(
            'Export error',
            [
                'id'   => $objectId,
                'type' => $type,
            ]
        );
    }
}
