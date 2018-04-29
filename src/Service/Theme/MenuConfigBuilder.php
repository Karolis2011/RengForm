<?php

namespace App\Service\Theme;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MenuBuilder
 */
class MenuConfigBuilder
{
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
     * @param array $config
     * @return array
     */
    public function build(array $config)
    {
        $route = $this->requestStack->getMasterRequest()->get('_route');

        foreach ($config as $name => $item) {
            if ($item['route'] == $route
                || (isset($item['child_routes']) && in_array($route, $item['child_routes']))
            ) {
                $config[$name]['active'] = true;
                break;
            }
        }

        return $config;
    }
}
