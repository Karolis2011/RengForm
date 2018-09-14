<?php

namespace App\Controller\Event;

use App\Entity\EventTime;
use App\Entity\Registration;
use App\Entity\WorkshopTime;
use App\Repository\EventTimeRepository;
use App\Repository\RegistrationRepository;
use App\Repository\WorkshopTimeRepository;
use App\Service\Form\FormValidator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController
 */
class RegistrationController extends Controller
{
    /**
     * @var WorkshopTimeRepository
     */
    private $workshopTimeRepository;

    /**
     * @var EventTimeRepository
     */
    private $eventTimeRepository;

    /**
     * @var RegistrationRepository
     */
    private $registrationRepository;

    /**
     * @var FormValidator
     */
    private $formValidator;

    /**
     * RegistrationController constructor.
     * @param WorkshopTimeRepository $workshopTimeRepository
     * @param EventTimeRepository    $eventTimeRepository
     * @param RegistrationRepository $registrationRepository
     * @param FormValidator          $formValidator
     */
    public function __construct(
        WorkshopTimeRepository $workshopTimeRepository,
        EventTimeRepository $eventTimeRepository,
        RegistrationRepository $registrationRepository,
        FormValidator $formValidator
    ) {
        $this->workshopTimeRepository = $workshopTimeRepository;
        $this->eventTimeRepository = $eventTimeRepository;
        $this->registrationRepository = $registrationRepository;
        $this->formValidator = $formValidator;
    }

    /**
     * @param $timeId
     * @return Response
     */
    public function register($timeId)
    {
        $time = $this->workshopTimeRepository->find($timeId);

        if ($time !== null) {
            $formConfig = $time->getWorkshop()->getFormConfig();
            $groupFormConfig = $time->getWorkshop()->getGroupFormConfig();

            if ($formConfig !== null && $groupFormConfig === null) {
                return $this->redirectToRoute('registration_simple', ['timeId' => $timeId]);
            }

            if ($formConfig === null && $groupFormConfig !== null) {
                return $this->redirectToRoute('registration_multi', ['timeId' => $timeId]);
            }

            return $this->render(
                'Default/formChoice.html.twig',
                [
                    'timeId' => $timeId,
                ]
            );
        }

        $time = $this->eventTimeRepository->find($timeId);

        if ($time !== null) {
            $formConfig = $time->getEvent()->getFormConfig();
            $groupFormConfig = $time->getEvent()->getGroupFormConfig();

            if ($formConfig !== null && $groupFormConfig === null) {
                return $this->redirectToRoute('registration_simple', ['timeId' => $timeId]);
            }

            if ($formConfig === null && $groupFormConfig !== null) {
                return $this->redirectToRoute('registration_multi', ['timeId' => $timeId]);
            }

            return $this->render(
                'Default/formChoice.html.twig',
                [
                    'timeId' => $timeId,
                ]
            );
        }

        throw new NotFoundHttpException(sprintf('Workshop or Event by id %s not found', $timeId));
    }

    /**
     * @param Request       $request
     * @param               $timeId
     * @return Response
     */
    public function registerSingle(Request $request, $timeId)
    {
        $formData = $request->get('registration', null);
        $time = $this->workshopTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processWorkshop($time, $formData);
        }

        $time = $this->eventTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processEvent($time, $formData);
        }

        throw new NotFoundHttpException(sprintf('Workshop or Event by id %s not found', $timeId));
    }

    /**
     * @param Request       $request
     * @param               $timeId
     * @return Response
     */
    public function registerMulti(Request $request, $timeId)
    {
        $formData = $request->get('registration', null);
        $time = $this->workshopTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processWorkshop($time, $formData, true);
        }

        $time = $this->eventTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processEvent($time, $formData, true);
        }

        throw new NotFoundHttpException(sprintf('Workshop or Event by id %s not found', $timeId));
    }

    /**
     * @param EventTime $eventTime
     * @param array     $formData
     * @param bool      $group
     * @return Response
     */
    private function processEvent(EventTime $eventTime, $formData, bool $group = false): Response
    {
        if (!$eventTime->isAvailable()) {
            $this->addFlash('danger', 'Event is full.');
        } elseif ($formData !== null) {
            if (!$this->formValidator->validate($eventTime, $formData)) {
                $this->addFlash('danger', 'Registration form is not filled correctly');
            } else {
                $registration = new Registration();
                $registration->setData($formData);
                $registration->setEventTime($eventTime);
                $registration->setGroupRegistration($group);
                $this->registrationRepository->save($registration);
                $eventTime->increaseEntries();
                $this->eventTimeRepository->update($eventTime);
                $this->addFlash('success', 'Registration successful');
            }
        }

        return $this->render(
            'Default/event.html.twig',
            [
                'eventTime'         => $eventTime,
                'groupRegistration' => $group,
            ]
        );
    }

    /**
     * @param WorkshopTime $workshopTime
     * @param array        $formData
     * @param bool         $group
     * @return Response
     */
    private function processWorkshop(WorkshopTime $workshopTime, $formData, bool $group = false): Response
    {
        if (!$workshopTime->isAvailable()) {
            $this->addFlash('danger', 'Workshop is full.');
        } elseif ($formData !== null) {
            if (!$this->formValidator->validate($workshopTime, $formData)) {
                $this->addFlash('danger', 'Registration form is not filled correctly');
            } else {
                $registration = new Registration();
                $registration->setData($formData);
                $registration->setWorkshopTime($workshopTime);
                $registration->setGroupRegistration($group);
                $this->registrationRepository->save($registration);
                $workshopTime->increaseEntries();
                $this->workshopTimeRepository->update($workshopTime);
                $this->addFlash('success', 'Registration successful');
            }
        }

        return $this->render(
            'Default/workshop.html.twig',
            [
                'workshopTime'      => $workshopTime,
                'groupRegistration' => $group,
            ]
        );
    }
}
