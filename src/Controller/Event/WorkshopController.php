<?php

namespace App\Controller\Event;

use App\Entity\Registration;
use App\Entity\Workshop;
use App\Repository\RegistrationRepository;
use App\Repository\WorkshopRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WorkshopController
 */
class WorkshopController extends Controller
{
    /**
     * @var WorkshopRepository
     */
    private $repository;

    /**
     * WorkshopController constructor.
     * @param WorkshopRepository $repository
     */
    public function __construct(WorkshopRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/register/{workshopId}", name="registration")
     * @param Request                $request
     * @param RegistrationRepository $registrationRepository
     * @param                        $workshopId
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, RegistrationRepository $registrationRepository, $workshopId)
    {
        /** @var Workshop $workshop */
        $workshop = $this->repository->find($workshopId);

        if ($workshop === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopId));
        }

        $formData = $request->get('registration', null);

        if ($formData !== null) {
            //TODO: validation
            $registration = new Registration();
            $registration->setData($formData);
            $registration->setWorkshop($workshop);
            $registrationRepository->save($registration);
            //TODO: display successful registration message
        }

        return $this->render(
            'Default/workshop.html.twig',
            [
                'workshop' => $workshop,
            ]
        );
    }
}
