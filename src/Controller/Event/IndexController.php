<?php

namespace App\Controller\Event;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('Default/index.html.twig');
    }
}
