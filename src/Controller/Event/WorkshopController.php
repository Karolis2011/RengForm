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
     * @var RegistrationRepository
     */
    private $registrationRepository;

    /**
     * WorkshopController constructor.
     * @param WorkshopRepository     $repository
     * @param RegistrationRepository $registrationRepository
     */
    public function __construct(WorkshopRepository $repository, RegistrationRepository $registrationRepository)
    {
        $this->repository = $repository;
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * @Route("/register/{workshopId}", name="registration")
     * @param Request $request
     * @param         $workshopId
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, $workshopId)
    {
        /** @var Workshop $workshop */
        $workshop = $this->repository->find($workshopId);

        if ($workshop === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $workshopId));
        }

        $formData = $request->get('registration', null);

        if ($workshop->getCapacity() !== null && $workshop->getEntries() >= $workshop->getCapacity()) {
            $this->addFlash('error', 'Workshop is full.');
        } elseif (empty($formData) || !$this->valid($workshop, $formData)) {
            $this->addFlash('error', 'Registration form is not filled correctly');
        } else {
            $registration = new Registration();
            $registration->setData($formData);
            $registration->setWorkshop($workshop);
            $this->registrationRepository->save($registration);
            $workshop->increaseEntries();
            $this->repository->save($workshop);
            $this->addFlash('success', 'Registration successful');
        }

        return $this->render(
            'Default/workshop.html.twig',
            [
                'workshop' => $workshop,
            ]
        );
    }

    /**
     * @param Workshop $workshop
     * @param array    $formData
     * @return bool
     */
    private function valid(Workshop $workshop, array $formData): bool
    {
        $valid = true;

        //TODO: validation

        return $valid;
    }
}
