<?php

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     * @param EventRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EventRepository $repository)
    {
        // replace this line with your own code!
        return $this->render(
            'Default/index.html.twig',
            [
                'events' => $repository->findAll(),
            ]
        );
    }
}
