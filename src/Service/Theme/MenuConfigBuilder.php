<?php

namespace App\Service\Theme;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * MenuConfigBuilder constructor.
     * @param RequestStack          $requestStack
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(RequestStack $requestStack, ParameterBagInterface $parameterBag)
    {
        $this->requestStack = $requestStack;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param array $config
     * @return array
     */
    public function build(array $config)
    {
        $route = $this->requestStack->getMasterRequest()->get('_route');

        foreach ($config as $name => $item) {
            if (isset($item['toggle']) && !$this->parameterBag->get($item['toggle'])) {
                unset($config[$name]);
            }
        }

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
