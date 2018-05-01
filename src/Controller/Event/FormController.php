<?php

namespace App\Controller\Event;

use App\Service\Form\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FormController
 */
class FormController extends Controller
{
    /**
     * @param array $config
     * @return Response
     */
    public function renderForm(array $config): Response
    {
        $form = new Form($config);

        return $this->render(
            'Default/form.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
