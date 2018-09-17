<?php

namespace App\Controller\Event;

use App\Entity\EmailTemplate;
use App\Entity\EventTime;
use App\Entity\Registration;
use App\Entity\WorkshopTime;
use App\Repository\EventTimeRepository;
use App\Repository\RegistrationRepository;
use App\Repository\WorkshopTimeRepository;
use App\Service\Form\ConfigDecorator;
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
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * RegistrationController constructor.
     * @param WorkshopTimeRepository $workshopTimeRepository
     * @param EventTimeRepository    $eventTimeRepository
     * @param RegistrationRepository $registrationRepository
     * @param FormValidator          $formValidator
     * @param \Swift_Mailer          $mailer
     * @param \Twig_Environment      $twig
     */
    public function __construct(
        WorkshopTimeRepository $workshopTimeRepository,
        EventTimeRepository $eventTimeRepository,
        RegistrationRepository $registrationRepository,
        FormValidator $formValidator,
        \Swift_Mailer $mailer,
        \Twig_Environment $twig
    ) {
        $this->workshopTimeRepository = $workshopTimeRepository;
        $this->eventTimeRepository = $eventTimeRepository;
        $this->registrationRepository = $registrationRepository;
        $this->formValidator = $formValidator;
        $this->mailer = $mailer;
        $this->twig = $twig;
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
    public function registerSimple(Request $request, $timeId)
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
     * @throws \Exception
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
     * @throws \Exception
     */
    private function processEvent(EventTime $eventTime, $formData, bool $group = false): Response
    {
        if (!$eventTime->isAvailable()) {
            $this->addFlash('danger', 'Event is full.');
        } elseif ($formData !== null) {
            if (!$this->formValidator->validate($eventTime, $formData, $group)) {
                $this->addFlash('danger', 'Registration form is not filled correctly');
            } else {
                $registration = new Registration();
                $registration->setData($formData);
                $registration->setEventTime($eventTime);
                $registration->setGroupRegistration($group);

                $entries = 1;
                if ($group) {
                    if (!isset($formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME])) {
                        throw new \Exception(sprintf(
                            'Got group registration for event %s without group count field set',
                            $eventTime->getEvent()->getId()
                        ));
                    }
                    $entries = $formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME];
                    $registration->setRegistrationSize($entries);
                }
                $eventTime->increaseEntries($entries);

                $this->registrationRepository->save($registration);
                $this->eventTimeRepository->update($eventTime);
                $this->addFlash('success', 'Registration successful');

                if ($group) {
                    $emailTemplate = $eventTime->getEvent()->getGroupFormConfig()->getEmailTemplate();
                } else {
                    $emailTemplate = $eventTime->getEvent()->getFormConfig()->getEmailTemplate();
                }
                $this->sendEmail($emailTemplate, $formData, [
                    'type'        => 'event',
                    'title'       => $eventTime->getEvent()->getTitle(),
                    'description' => $eventTime->getEvent()->getDescription(),
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
    private function processWorkshop(WorkshopTime $workshopTime, $formData, bool $group = false): Response
    {
        if (!$workshopTime->isAvailable()) {
            $this->addFlash('danger', 'Workshop is full.');
        } elseif ($formData !== null) {
            if (!$this->formValidator->validate($workshopTime, $formData, $group)) {
                $this->addFlash('danger', 'Registration form is not filled correctly');
            } else {
                $registration = new Registration();
                $registration->setData($formData);
                $registration->setWorkshopTime($workshopTime);
                $registration->setGroupRegistration($group);

                $entries = 1;
                if ($group) {
                    if (!isset($formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME])) {
                        throw new \Exception(sprintf(
                            'Got group registration for event %s without group count field set',
                            $workshopTime->getWorkshop()->getId()
                        ));
                    }
                    $entries = $formData[ConfigDecorator::GROUP_COUNT_FIELD_NAME];
                    $registration->setRegistrationSize($entries);
                }
                $workshopTime->increaseEntries($entries);

                $this->registrationRepository->save($registration);
                $this->workshopTimeRepository->update($workshopTime);
                $this->addFlash('success', 'Registration successful');

                if ($group) {
                    $emailTemplate = $workshopTime->getWorkshop()->getGroupFormConfig()->getEmailTemplate();
                } else {
                    $emailTemplate = $workshopTime->getWorkshop()->getFormConfig()->getEmailTemplate();
                }
                $this->sendEmail($emailTemplate, $formData, [
                    'type'        => 'workshop',
                    'title'       => $workshopTime->getWorkshop()->getTitle(),
                    'description' => $workshopTime->getWorkshop()->getDescription(),
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

        //TODO: Format the email
        $template = $this->twig->createTemplate($emailTemplate->getBody());
        $message = (new \Swift_Message($emailTemplate->getTitle()))
            ->setTo($recipient)
            ->setFrom($this->getParameter('sender_email'))
            ->setBody(
                $template->render([
                    'data'  => $formData,
                    'extra' => $extraData,
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
