<?php

namespace App\Controller\Admin;

use App\Entity\EmailTemplate;
use App\Entity\FormConfig;
use App\Form\EmailTemplateType;
use App\Repository\EmailTemplateRepository;
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
     * @var EmailTemplateRepository
     */
    private $repository;

    /**
     * EmailController constructor.
     * @param EmailTemplateRepository $repository
     */
    public function __construct(EmailTemplateRepository $repository)
    {
        $this->repository = $repository;
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

        $emailTemplate = new EmailTemplate();
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
                'form_fields' => $emailTemplate->getFormConfig()->getFieldNames()
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
        $emailTemplate = $this->getEmailTemplate($emailTemplateId);

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
                'form_fields'   => $emailTemplate->getFormConfig()->getFieldNames()
            ]
        );
    }

    /**
     * @param $emailTemplateId
     * @return RedirectResponse
     */
    public function delete($emailTemplateId)
    {
        $emailTemplate = $this->getEmailTemplate($emailTemplateId);

        if ($emailTemplate === null) {
            throw new NotFoundHttpException(sprintf('Email template by id %s not found', $emailTemplateId));
        }

        $fieldConfigId = $emailTemplate->getFormConfig()->getId();

        $this->repository->remove($emailTemplate);
        $this->addFlash('success', "Email template successfully deleted");

        return $this->redirectToRoute('form_show', ['formId' => $fieldConfigId]);
    }

    /**
     * @param $emailTemplateId
     * @return EmailTemplate|null
     */
    private function getEmailTemplate($emailTemplateId): ?EmailTemplate
    {
        $emailTemplate = $this->repository->find($emailTemplateId);

        if ($emailTemplate !== null && !$this->isOwner($emailTemplate->getFormConfig()->getOwner())) {
            return null;
        }

        return $emailTemplate;
    }
}
