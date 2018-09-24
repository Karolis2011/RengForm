<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\MultiEvent;
use App\Entity\OneTimeEmailTemplate;
use App\Entity\RegistrationEmailTemplate;
use App\Entity\FormConfig;
use App\Form\EmailTemplateType;
use App\Form\OneTimeEmailTemplateType;
use App\Repository\EventRepository;
use App\Repository\MultiEventRepository;
use App\Repository\OneTimeEmailTemplateRepository;
use App\Repository\RegistrationEmailTemplateRepository;
use App\Repository\FormConfigRepository;
use App\Service\Helper\SharedAmongUsersTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EmailController
 */
class EmailController extends Controller
{
    use SharedAmongUsersTrait;

    /**
     * @var RegistrationEmailTemplateRepository
     */
    private $repository;

    /**
     * @var OneTimeEmailTemplateRepository
     */
    private $oneTimeRepository;

    /**
     * EmailController constructor.
     * @param RegistrationEmailTemplateRepository $repository
     * @param OneTimeEmailTemplateRepository      $oneTimeRepository
     */
    public function __construct(
        RegistrationEmailTemplateRepository $repository,
        OneTimeEmailTemplateRepository $oneTimeRepository
    ) {
        $this->repository = $repository;
        $this->oneTimeRepository = $oneTimeRepository;
    }

    /**
     * @param Request              $request
     * @param FormConfigRepository $formConfigRepository
     * @param                      $formId
     * @return RedirectResponse|Response
     */
    public function create(Request $request, FormConfigRepository $formConfigRepository, $formId)
    {
        /** @var FormConfig|null $formConfig */
        $formConfig = $this->findEntity($formConfigRepository, $formId);

        if ($formConfig === null) {
            throw new NotFoundHttpException(sprintf('Form config by id %s not found', $formId));
        }

        $emailTemplate = new RegistrationEmailTemplate();
        $form = $this->createForm(
            EmailTemplateType::class,
            $emailTemplate,
            [
                'form_fields' => $formConfig->getFieldNames(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailTemplate->setFormConfig($formConfig);
            $this->repository->save($emailTemplate);

            return $this->redirectToRoute(
                'form_show',
                [
                    'formId' => $formId,
                ]
            );
        }

        return $this->render(
            'Admin/EmailTemplate/create.html.twig',
            [
                'form'        => $form->createView(),
                'form_fields' => $formConfig->getFieldNames(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param         $emailTemplateId
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, $emailTemplateId)
    {
        $emailTemplate = $this->getRegistrationEmailTemplate($emailTemplateId);

        if ($emailTemplate === null) {
            throw new NotFoundHttpException(sprintf('Email template by id %s not found', $emailTemplateId));
        }

        $form = $this->createForm(
            EmailTemplateType::class,
            $emailTemplate,
            [
                'form_fields' => $emailTemplate->getFormConfig()->getFieldNames(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->update($emailTemplate);

            return $this->redirectToRoute(
                'form_show',
                [
                    'formId' => $emailTemplate->getFormConfig()->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/EmailTemplate/update.html.twig',
            [
                'form'          => $form->createView(),
                'emailTemplate' => $emailTemplate,
                'form_fields'   => $emailTemplate->getFormConfig()->getFieldNames(),
            ]
        );
    }

    /**
     * @param $emailTemplateId
     * @return RedirectResponse
     */
    public function delete($emailTemplateId)
    {
        $emailTemplate = $this->getRegistrationEmailTemplate($emailTemplateId);

        if ($emailTemplate === null) {
            throw new NotFoundHttpException(sprintf('Email template by id %s not found', $emailTemplateId));
        }

        $fieldConfigId = $emailTemplate->getFormConfig()->getId();

        $this->repository->remove($emailTemplate);
        $this->addFlash('success', "Email template successfully deleted");

        return $this->redirectToRoute('form_show', ['formId' => $fieldConfigId]);
    }

    /**
     * @param EventRepository      $eventRepository
     * @param MultiEventRepository $multiEventRepository
     * @param string               $eventId
     * @return Response|RedirectResponse
     */
    public function sendEvent(
        EventRepository $eventRepository,
        MultiEventRepository $multiEventRepository,
        Request $request,
        $eventId
    ) {
        /** @var MultiEvent|null $event */
        $event = $this->findEntity($multiEventRepository, $eventId);

        if ($event !== null) {
            $this->addFlash('danger', 'Email message can only be sent to simple event or multi events workshop');

            return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
        }

        /** @var Event|null $event */
        $event = $this->findEntity($eventRepository, $eventId);

        if ($event !== null) {
            $emailTemplate = new OneTimeEmailTemplate();
            $form = $this->createForm(
                OneTimeEmailTemplateType::class,
                $emailTemplate
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $emailTemplate->setEvent($event);
                $this->oneTimeRepository->save($emailTemplate);
                $this->addFlash('success', 'Email sent.');

                // TODO: send emails

                return $this->redirectToRoute('event_show', ['eventId' => $eventId]);
            }

            return $this->render(
                'Admin/OneTimeEmail/create.html.twig',
                [
                    'form'        => $form->createView(),
                ]
            );
        }

        throw new NotFoundHttpException(sprintf('Event by id %s not found', $eventId));
    }

    /**
     * @param $emailTemplateId
     * @return RegistrationEmailTemplate|null
     */
    private function getRegistrationEmailTemplate($emailTemplateId): ?RegistrationEmailTemplate
    {
        $emailTemplate = $this->repository->find($emailTemplateId);

        if ($emailTemplate !== null && !$this->isOwner($emailTemplate->getFormConfig()->getOwner())) {
            return null;
        }

        return $emailTemplate;
    }
}
