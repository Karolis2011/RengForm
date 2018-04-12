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
        'events' => [
            'title'        => 'Events',
            'icon'         => 'fa fa-link',
            'route'        => 'event_index',
            'child_routes' => [
                'event_create',
                'event_create_multi',
                'event_show',
                'event_delete',
                'event_update_multi',
                'event_save_category_order',
                'event_times_update',
                'workshop_create',
                'event_update',
                'category_create',
                'category_update',
                'workshop_update',
                'workshop_registrations',
                'workshop_update_times',
            ],
        ],
        'forms'  => [
            'title'        => 'Forms',
            'icon'         => 'fa fa-link',
            'route'        => 'form_index',
            'child_routes' => [
                'form_create',
                'form_show',
                'form_update',
            ],
        ],
    ];

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * MenuBuilder constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array
     */
    public function build()
    {
        $config = $this->config;
        $route = $this->requestStack->getMasterRequest()->get('_route');

        foreach ($config as $name => $item) {
            if ($item['route'] == $route || in_array($route, $item['child_routes'])) {
                $config[$name]['active'] = true;
                break;
            }
        }

        return $config;
    }
}
