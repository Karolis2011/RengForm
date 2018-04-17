<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\FormConfig;
use App\Entity\Workshop;
use App\Form\FormConfigType;
use App\Repository\FormConfigRepository;
use App\Service\Form\ConfigDecorator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FormController
 */
class FormController extends Controller
{
    /**
     * @var FormConfigRepository
     */
    private $repository;

    /**
     * FormController constructor.
     * @param FormConfigRepository $repository
     */
    public function __construct(FormConfigRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $formConfigs = $this->repository->findBy(['owner' => $user]);

        return $this->render(
            'Admin/Form/index.html.twig',
            [
                'formConfigs' => $formConfigs,
            ]
        );
    }

    /**
     * @param $formId
     * @return Response
     */
    public function show($formId)
    {
        $formConfig = $this->repository->find($formId);

        if ($formConfig === null) {
            throw new NotFoundHttpException(sprintf('Form by id %s not found', $formId));
        }

        return $this->render(
            'Admin/Form/show.html.twig',
            [
                'formConfig' => $formConfig,
            ]
        );
    }

    /**
     * @param Request         $request
     * @param ConfigDecorator $decorator
     * @return RedirectResponse|Response
     */
    public function create(Request $request, ConfigDecorator $decorator)
    {
        $formConfig = new FormConfig();
        $form = $this->createForm(FormConfigType::class, $formConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decorator->decorate($formConfig);
            $formConfig->setOwner($this->getUser());
            $this->repository->save($formConfig);

            return $this->redirectToRoute(
                'form_show',
                [
                    'formId' => $formConfig->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Form/create.html.twig',
            [
                'form'       => $form->createView(),
                'formConfig' => $formConfig,
            ]
        );
    }

    /**
     * @param Request         $request
     * @param ConfigDecorator $decorator
     * @param                $formId
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, ConfigDecorator $decorator, $formId)
    {
        $formConfig = $this->repository->find($formId);

        if ($formConfig === null) {
            throw new NotFoundHttpException(sprintf('Form by id %s not found', $formId));
        }

        $form = $this->createForm(FormConfigType::class, $formConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decorator->decorate($formConfig);
            $this->repository->update($formConfig);

            return $this->redirectToRoute(
                'form_show',
                [
                    'formId' => $formConfig->getId(),
                ]
            );
        }

        return $this->render(
            'Admin/Form/update.html.twig',
            [
                'form'       => $form->createView(),
                'formConfig' => $formConfig,
            ]
        );
    }

    /**
     * @param $formId
     * @return RedirectResponse
     */
    public function delete($formId)
    {
        $formConfig = $this->repository->find($formId);

        if ($formConfig === null) {
            throw new NotFoundHttpException(sprintf('Form by id %s not found', $formId));
        }

        $usesCount = count(
            $this->getDoctrine()
                ->getRepository(Workshop::class)
                ->findBy(['formConfig' => $formConfig])
        );
        $usesCount += count(
            $this->getDoctrine()
                ->getRepository(Event::class)
                ->findBy(['formConfig' => $formConfig])
        );

        if ($usesCount > 0) {
            $this->addFlash('danger', "Form is used somewhere and can't be deleted");

            return $this->redirectToRoute('form_show', ['formId' => $formId]);
        } else {
            $this->repository->remove($formConfig);
            $this->addFlash('success', "Form successfully deleted");

            return $this->redirectToRoute('form_index');
        }
    }
}
