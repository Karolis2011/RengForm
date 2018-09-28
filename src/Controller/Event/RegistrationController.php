<?php

namespace App\Controller\Event;

use App\Entity\EmailTemplate;
use App\Entity\EventTime;
use App\Entity\Registration;
use App\Entity\WorkshopTime;
use App\Repository\EventTimeRepository;
use App\Repository\RegistrationRepository;
use App\Repository\WorkshopTimeRepository;
use App\Service\Email\Mailer;
use App\Service\Form\ConfigDecorator;
use App\Service\Form\FormValidator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @var Mailer
     */
    private $mailer;

    /**
     * RegistrationController constructor.
     * @param WorkshopTimeRepository $workshopTimeRepository
     * @param EventTimeRepository    $eventTimeRepository
     * @param RegistrationRepository $registrationRepository
     * @param FormValidator          $formValidator
     * @param Mailer                 $mailer
     */
    public function __construct(
        WorkshopTimeRepository $workshopTimeRepository,
        EventTimeRepository $eventTimeRepository,
        RegistrationRepository $registrationRepository,
        FormValidator $formValidator,
        Mailer $mailer
    ) {
        $this->workshopTimeRepository = $workshopTimeRepository;
        $this->eventTimeRepository = $eventTimeRepository;
        $this->registrationRepository = $registrationRepository;
        $this->formValidator = $formValidator;
        $this->mailer = $mailer;
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
     * @throws \Exception
     */
    public function registerSimple(Request $request, $timeId, AuthorizationCheckerInterface $authChecker)
    {
        $formData = $request->get('registration', null);
        $time = $this->workshopTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processWorkshop($time, $formData, false, $authChecker);
        }

        $time = $this->eventTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processEvent($time, $formData, false, $authChecker);
        }

        throw new NotFoundHttpException(sprintf('Workshop or Event by id %s not found', $timeId));
    }

    /**
     * @param Request       $request
     * @param               $timeId
     * @return Response
     * @throws \Exception
     */
    public function registerMulti(Request $request, $timeId, AuthorizationCheckerInterface $authChecker)
    {
        $formData = $request->get('registration', null);
        $time = $this->workshopTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processWorkshop($time, $formData, true, $authChecker);
        }

        $time = $this->eventTimeRepository->find($timeId);

        if ($time !== null) {
            return $this->processEvent($time, $formData, true, $authChecker);
        }

        throw new NotFoundHttpException(sprintf('Workshop or Event by id %s not found', $timeId));
    }

    /**
     * @param EventTime $eventTime
     * @param array     $formData
     * @param bool      $group
     * @return Response
     * @throws \Exception
     */
    private function processEvent(EventTime $eventTime, $formData, bool $group = false, AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$eventTime->isAvailable($authChecker->isGranted('ROLE_USER'))) {
            if($eventTime->getEntriesLeft() <= 0) {
                $this->addFlash('danger', 'Event is full.');
            } else {
                $this->addFlash('danger', 'Registration for this event is closed.');
            }
        } elseif ($formData !== null) {
            $errors = $this->formValidator->validate($eventTime, $formData, $group);
            if (!empty($errors)) {
                $this->addFlash('danger', 'Registration form is not filled correctly');

                // THIS OUTPUTS NOT TRANSLATED MESSAGES!!!
                foreach ($errors as $error) {
                    $this->addFlash('danger', $error);
                }
            } else {
                $entries = 1;
                if ($group) {
                    if (!isset($formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME])) {
                        throw new \Exception(sprintf(
                            'Got group registration for event %s without group count field set',
                            $eventTime->getEvent()->getId()
                        ));
                    }
                    $entries = $formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME];

                    // Check if there are enough space for a group
                    if ($eventTime->getEvent()->getCapacity() !== null && $eventTime->getEntriesLeft() < $entries) {
                        //Not enough free spaces left in event
                        $this->addFlash('danger', 'Event does not have enough space left.');

                        return $this->render(
                            'Default/event.html.twig',
                            [
                                'eventTime'         => $eventTime,
                                'groupRegistration' => $group,
                            ]
                        );
                    }
                }
                $registration = new Registration();
                $registration->setData($formData);
                $registration->setEventTime($eventTime);
                $registration->setGroupRegistration($group);
                $registration->setRegistrationSize($entries);

                $eventTime->increaseEntries($entries);

                $this->registrationRepository->save($registration);
                $this->eventTimeRepository->update($eventTime);
                $this->addFlash('success', 'Registration successful');

                if ($group) {
                    $emailTemplate = $eventTime->getEvent()->getGroupFormConfig()->getRegistrationEmailTemplate();
                } else {
                    $emailTemplate = $eventTime->getEvent()->getFormConfig()->getRegistrationEmailTemplate();
                }

                $this->sendEmail($emailTemplate, $formData, [
                    'type'        => 'event',
                    'title'       => $eventTime->getEvent()->getTitle(),
                    'description' => $eventTime->getEvent()->getDescription(),
                    'time'        => $eventTime->getStartTime()->format('Y-m-d H:i'),
                    'duration'    => $eventTime->getEvent()->getDuration()->format('H:i'),
                ]);
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
     * @throws \Exception
     */
    private function processWorkshop(WorkshopTime $workshopTime, $formData, bool $group = false, AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$workshopTime->isAvailable($authChecker->isGranted('ROLE_USER'))) {
            if($workshopTime->getEntriesLeft() <= 0) {
                $this->addFlash('danger', 'Workshop is full.');
            } else {
                $this->addFlash('danger', 'Registration for this workshop is closed.');
            }
        } elseif ($formData !== null) {
            $errors = $this->formValidator->validate($workshopTime, $formData, $group);
            if (!empty($errors)) {
                $this->addFlash('danger', 'Registration form is not filled correctly');

                // THIS OUTPUTS NOT TRANSLATED MESSAGES!!!
                foreach ($errors as $error) {
                    $this->addFlash('danger', $error);
                }
            } else {
                $entries = 1;
                if ($group) {
                    if (!isset($formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME])) {
                        throw new \Exception(sprintf(
                            'Got group registration for event %s without group count field set',
                            $workshopTime->getWorkshop()->getId()
                        ));
                    }
                    $entries = $formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME];

                    // Check if there are enough space for a group
                    if ($workshopTime->getWorkshop()->getCapacity() !== null
                        && $workshopTime->getEntriesLeft() < $entries
                    ) {
                        //Not enough free spaces left in event
                        $this->addFlash('danger', 'Workshop does not have enough space left.');

                        return $this->render(
                            'Default/workshop.html.twig',
                            [
                                'workshopTime'      => $workshopTime,
                                'groupRegistration' => $group,
                            ]
                        );
                    }
                }

                $registration = new Registration();
                $registration->setData($formData);
                $registration->setWorkshopTime($workshopTime);
                $registration->setGroupRegistration($group);
                $registration->setRegistrationSize($entries);

                $workshopTime->increaseEntries($entries);

                $this->registrationRepository->save($registration);
                $this->workshopTimeRepository->update($workshopTime);
                $this->addFlash('success', 'Registration successful');

                if ($group) {
                    $emailTemplate = $workshopTime->getWorkshop()->getGroupFormConfig()->getRegistrationEmailTemplate();
                } else {
                    $emailTemplate = $workshopTime->getWorkshop()->getFormConfig()->getRegistrationEmailTemplate();
                }

                $this->sendEmail($emailTemplate, $formData, [
                    'type'        => 'workshop',
                    'title'       => $workshopTime->getWorkshop()->getTitle(),
                    'description' => $workshopTime->getWorkshop()->getDescription(),
                    'time'        => $workshopTime->getStartTime()->format('Y-m-d H:i'),
                    'duration'    => $workshopTime->getWorkshop()->getDuration()->format('H:i'),
                ]);
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

    /**
     * @param EmailTemplate|null $emailTemplate
     * @param array              $formData
     * @param array              $extraData
     */
    private function sendEmail(?EmailTemplate $emailTemplate, array $formData, array $extraData): void
    {
        if ($emailTemplate === null) {
            return;
        }

        if (!isset($formData[$emailTemplate->getReceiverField()])) {
            //TODO: log that form was missing a recipient field
            return;
        }

        $recipient = $formData[$emailTemplate->getReceiverField()];
        $data = [
            'data'  => $formData,
            'extra' => $extraData,
        ];

        $this->mailer->sendEmail($emailTemplate, $recipient, $data);
    }
}
