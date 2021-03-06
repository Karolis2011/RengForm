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
     * @param string            $view
     * @return Response
     */
    public function menu(MenuConfigBuilder $builder, string $view): Response
    {
        $menuConfig = $this->getParameter('menu_config.' . $view);

        return $this->render(
            'Admin/Theme/menu.html.twig',
            [
                'config' => $builder->build($menuConfig),
            ]
        );
    }
}
