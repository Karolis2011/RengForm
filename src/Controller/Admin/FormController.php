<?php

namespace App\Controller\Admin;

use App\Entity\FormConfig;
use App\Form\FormConfigType;
use App\Repository\FormConfigRepository;
use App\Service\Form\ConfigEnricher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        /** @var FormConfig[] $formConfigs */
        $formConfigs = $this->repository->findAll();

        return $this->render(
            'Admin/Form/index.html.twig',
            [
                'formConfigs' => $formConfigs,
            ]
        );
    }

    /**
     * @param Request        $request
     * @param ConfigEnricher $enricher
     * @return Response
     */
    public function create(Request $request, ConfigEnricher $enricher)
    {
        $formConfig = new FormConfig();
        $form = $this->createForm(FormConfigType::class, $formConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formConfig->setConfig(json_decode($formConfig->getConfig(), true));
            $enricher->enrich($formConfig);
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
     * @param $formId
     * @return Response
     */
    public function show($formId)
    {
        /** @var FormConfig $formConfig */
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
     * @param Request        $request
     * @param ConfigEnricher $enricher
     * @param                $formId
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, ConfigEnricher $enricher, $formId)
    {
        /** @var FormConfig $formConfig */
        $formConfig = $this->repository->find($formId);

        if ($formConfig === null) {
            throw new \Exception(sprintf('Form by id %s not found', $formId));
        }

        $formConfig->setConfig(json_encode($formConfig->getConfig()));

        $form = $this->createForm(FormConfigType::class, $formConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formConfig->setConfig(json_decode($formConfig->getConfig(), true));
            $enricher->enrich($formConfig);
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
}
