<?php

namespace App\Controller\Admin;

use App\Repository\EventTimeRepository;
use App\Repository\RegistrationRepository;
use App\Repository\WorkshopRepository;
use App\Repository\WorkshopTimeRepository;
use App\Service\Helper\SharedAmongUsersTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController
 */
class RegistrationController extends Controller
{
    use SharedAmongUsersTrait;

    /**
     * @var WorkshopRepository
     */
    private $workshopRepository;

    /**
     * @var RegistrationRepository
     */
    private $repository;

    /**
     * RegistrationController constructor.
     * @param WorkshopRepository     $workshopRepository
     * @param RegistrationRepository $repository
     */
    public function __construct(WorkshopRepository $workshopRepository, RegistrationRepository $repository)
    {
        $this->workshopRepository = $workshopRepository;
        $this->repository = $repository;
    }

    /**
     * @param $workshopId
     * @return Response
     */
    public function index($workshopId)
    {
        $workshop = $this->workshopRepository->find($workshopId);

        if ($workshop === null || !$this->isOwner($workshop->getCategory()->getEvent()->getOwner())) {
            throw new NotFoundHttpException(sprintf('Workshop by id %s not found', $workshopId));
        }

        return $this->render(
            'Admin/Registration/index.html.twig',
            [
                'workshop' => $workshop,
            ]
        );
    }

    /**
     * @param EventTimeRepository    $eventTimeRepository
     * @param WorkshopTimeRepository $workshopTimeRepository
     * @param                        $registrationId
     * @return RedirectResponse
     */
    public function delete(
        EventTimeRepository $eventTimeRepository,
        WorkshopTimeRepository $workshopTimeRepository,
        $registrationId
    ) {
        $registration = $this->repository->find($registrationId);

        if ($registration === null) {
            throw new NotFoundHttpException(sprintf('Registration by id %s not found', $registrationId));
        }

        if ($registration->getEventTime() !== null) {
            $time = $registration->getEventTime();
            $time->increaseEntries(-1 * $registration->getRegistrationSize());
            $eventTimeRepository->update($time);
            $eventId = $time->getEvent()->getId();
            $this->repository->remove($registration);
            $this->addFlash('success', "Registration successfully deleted");

            return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
        }

        $time = $registration->getWorkshopTime();
        $time->increaseEntries(-1 * $registration->getRegistrationSize());
        $workshopTimeRepository->update($time);
        $this->repository->remove($registration);
        $this->addFlash('success', "Registration successfully deleted");
        $workshopId = $time->getWorkshop()->getId();

        return $this->redirectToRoute('workshop_registrations', ['workshopId' => $workshopId]);
    }
}
