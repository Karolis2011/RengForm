<?php

namespace App\Controller\Event;

use App\Service\Form\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FormController
 */
class FormController extends Controller
{
    /**
     * @param array $config
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderForm($config)
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
