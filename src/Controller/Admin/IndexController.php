<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index()
    {
        // replace this line with your own code!
        return $this->render('Admin/base.html.twig');
    }
}
