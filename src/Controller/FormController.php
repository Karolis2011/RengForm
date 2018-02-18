<?php

namespace App\Controller;

use App\Entity\FormConfig;
use App\Repository\FormConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FormController
 */
class FormController extends Controller
{
    /**
     * @Route("/form", name="form_index")
     * @return Response
     */
    public function index()
    {
        /** @var FormConfig[] $formConfigs */
        $formConfigs = $this->getRepository()->findAll();

        return $this->render(
            'Admin/Form/index.html.twig',
            [
                'formConfigs' => $formConfigs,
            ]
        );
    }

    /**
     * @Route("/form/create", name="form_create")
     * @return Response
     */
    public function create()
    {
        return new Response('create form');
    }

    /**
     * @Route("/form/{formId}", name="form_show")
     * @param $formId
     * @return Response
     */
    public function show($formId)
    {
        /** @var FormConfig $formConfig */
        $formConfig = $this->getRepository()->find($formId);

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
     * @param $formId
     * @return Response
     */
    public function update($formId)
    {
        return new Response('update form id: ' . $formId);
    }

    /**
     * @return FormConfigRepository
     */
    private function getRepository(): FormConfigRepository
    {
        $repository = $this->getDoctrine()->getRepository(FormConfig::class);

        return $repository;
    }
}
