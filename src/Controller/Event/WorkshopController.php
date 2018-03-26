<?php

namespace App\Controller\Event;

use App\Entity\Registration;
use App\Entity\WorkshopTime;
use App\Repository\RegistrationRepository;
use App\Repository\WorkshopTimeRepository;
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
     * @var WorkshopTimeRepository
     */
    private $repository;

    /**
     * @var RegistrationRepository
     */
    private $registrationRepository;

    /**
     * WorkshopController constructor.
     * @param WorkshopTimeRepository $repository
     * @param RegistrationRepository $registrationRepository
     */
    public function __construct(WorkshopTimeRepository $repository, RegistrationRepository $registrationRepository)
    {
        $this->repository = $repository;
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * @Route("/register/{timeId}", name="registration")
     * @param Request $request
     * @param         $timeId
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, $timeId)
    {
        /** @var WorkshopTime $workshopTime */
        $workshopTime = $this->repository->find($timeId);

        if ($workshopTime === null) {
            throw new \Exception(sprintf('Workshop by id %s not found', $timeId));
        }

        $formData = $request->get('registration', null);

        if (!$workshopTime->isAvailable()) {
            $this->addFlash('alert', 'Workshop is full.');
        } elseif ($formData !== null) {
            if (!$this->valid($workshopTime, $formData)) {
                $this->addFlash('alert', 'Registration form is not filled correctly');
            } else {
                $registration = new Registration();
                $registration->setData($formData);
                $registration->setWorkshopTime($workshopTime);
                $this->registrationRepository->save($registration);
                $workshopTime->increaseEntries();
                $this->repository->update($workshopTime);
                $this->addFlash('success', 'Registration successful');
            }
        }

        return $this->render(
            'Default/workshop.html.twig',
            [
                'workshopTime' => $workshopTime,
            ]
        );
    }

    /**
     * @param WorkshopTime $workshopTime
     * @param array        $formData
     * @return bool
     */
    private function valid(WorkshopTime $workshopTime, array $formData): bool
    {
        $valid = true;

        if (empty($formData)) {
            $valid = false;
        }
        //TODO: validate

        return $valid;
    }
}
