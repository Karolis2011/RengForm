<?php

namespace App\Service\Theme;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MenuBuilder
 */
class MenuConfigBuilder
{
    /**
     * @var array
     */
    private $config = [
        'home'   => [
            'title' => 'Home',
            'icon'  => 'fa fa-home',
            'route' => 'admin_index',
        ],
        'events' => [
            'title' => 'Events',
            'icon'  => 'fa fa-link',
            'route' => 'event_index',
        ],
        'forms'  => [
            'title' => 'Forms',
            'icon'  => 'fa fa-link',
            'route' => 'form_index',
        ],
    ];

    private $defaultActive = 'home';

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * MenuBuilder constructor.
     * @param \Twig_Environment $twig
     * @param RequestStack      $requestStack
     */
    public function __construct(\Twig_Environment $twig, RequestStack $requestStack)
    {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    /**
     * @return array
     */
    public function build()
    {
        $config = $this->config;
        $route = $this->requestStack->getMasterRequest()->get('_route');
        $foundActive = false;

        foreach ($config as $name => $item) {
            if ($item['route'] == $route) {
                $config[$name]['active'] = true;
                $foundActive = true;
                break;
            }
        }

        if (!$foundActive) {
            $config[$this->defaultActive]['active'] = true;
        }

        return $config;
    }
}
