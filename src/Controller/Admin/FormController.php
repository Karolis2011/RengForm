<?php

namespace App\Controller\Admin;

use App\Entity\FormConfig;
use App\Form\FormConfigType;
use App\Repository\FormConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/form", name="form_index")
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
     * @Route("/form/create", name="form_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $formConfig = new FormConfig();
        $form = $this->createForm(FormConfigType::class, $formConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formConfig->setConfig(json_decode($formConfig->getConfig()));
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
     * @Route("/form/{formId}", name="form_show")
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
     * @Route("/form/{formId}/update", name="form_update")
     * @param Request $request
     * @param         $formId
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, $formId)
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
            $formConfig->setConfig(json_decode($formConfig->getConfig()));
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
