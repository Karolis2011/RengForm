<?php

namespace App\Controller\Admin;

use App\Service\Theme\MenuConfigBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThemeController
 */
class ThemeController extends Controller
{
    /**
     * @param MenuConfigBuilder $builder
     * @return Response
     */
    public function menu(MenuConfigBuilder $builder)
    {
        return $this->render(
            'Admin/Theme/menu.html.twig',
            [
                'config' => $builder->build(),
            ]
        );
    }
}
