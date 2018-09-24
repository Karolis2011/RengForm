<?php

namespace App\Service\Email;

use App\Entity\EmailTemplate;
use App\Entity\Event;
use App\Entity\FormConfig;
use App\Entity\Workshop;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Mailer
 */
class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer         $mailer
     * @param \Twig_Environment     $twig
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param string        $recipient
     * @param array         $data
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function sendEmail(EmailTemplate $emailTemplate, string $recipient, array $data): void
    {
        $template = $this->twig->createTemplate($emailTemplate->getBody());
        $message = (new \Swift_Message($emailTemplate->getSubject()))
            ->setTo($recipient)
            ->setFrom($this->getSenderEmail())
            ->setBody(
                $template->render($data),
                'text/html'
            );

        $this->mailer->send($message);
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param Event         $event
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function sendEmailsEvent(EmailTemplate $emailTemplate, Event $event): void
    {
        $data = [
            'title'       => $event->getTitle(),
            'description' => $event->getDescription(),
            'place'       => $event->getPlace(),
            'duration'    => $event->getDuration()->format('H:i'),
        ];

        $recipientField = $this->getRecipientField($event->getFormConfig());
        $groupRecipientField = $this->getRecipientField($event->getGroupFormConfig());

        foreach ($event->getTimes() as $eventTime) {
            $data['time'] = $eventTime->getStartTime()->format('Y-m-d H:i');
            $recipients = [];

            foreach ($eventTime->getRegistrations() as $registration) {
                if ($registration->isGroupRegistration()) {
                    if (isset($registration->getData()[$groupRecipientField])) {
                        $recipients[] = $registration->getData()[$groupRecipientField];
                    }
                    // TODO: Log if no recipient
                } else {
                    if (isset($registration->getData()[$recipientField])) {
                        $recipients[] = $registration->getData()[$recipientField];
                    }
                    // TODO: Log if no recipient
                }
            }

            foreach ($recipients as $recipient) {
                $this->sendEmail($emailTemplate, $recipient, $data);
            }
        }
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param Workshop      $workshop
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function sendEmailsWorkshop(EmailTemplate $emailTemplate, Workshop $workshop): void
    {
        $data = [
            'title'       => $workshop->getTitle(),
            'description' => $workshop->getDescription(),
            'place'       => $workshop->getPlace(),
            'duration'    => $workshop->getDuration()->format('H:i'),
        ];

        $recipientField = $this->getRecipientField($workshop->getFormConfig());
        $groupRecipientField = $this->getRecipientField($workshop->getGroupFormConfig());

        foreach ($workshop->getTimes() as $eventTime) {
            $data['time'] = $eventTime->getStartTime()->format('Y-m-d H:i');
            $recipients = [];

            foreach ($eventTime->getRegistrations() as $registration) {
                if ($registration->isGroupRegistration()) {
                    if (isset($registration->getData()[$groupRecipientField])) {
                        $recipients[] = $registration->getData()[$groupRecipientField];
                    }
                    // TODO: Log if no recipient
                } else {
                    if (isset($registration->getData()[$recipientField])) {
                        $recipients[] = $registration->getData()[$recipientField];
                    }
                    // TODO: Log if no recipient
                }
            }

            foreach ($recipients as $recipient) {
                $this->sendEmail($emailTemplate, $recipient, $data);
            }
        }
    }

    /**
     * @param FormConfig $formConfig
     * @return null|string
     */
    private function getRecipientField(FormConfig $formConfig): ?string
    {
        if ($formConfig->getRegistrationEmailTemplate() !== null) {
            return $formConfig->getRegistrationEmailTemplate()->getReceiverField();
        }

        return null;
    }

    /**
     * @return string
     */
    private function getSenderEmail(): string
    {
        return $this->parameterBag->get('sender_email');
    }
}
