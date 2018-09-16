<?php

namespace App\Tests\Unit\Service\Theme;

use App\Service\Theme\MenuConfigBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MenuConfigBuilderTest
 */
class MenuConfigBuilderTest extends TestCase
{
    public function testBuild()
    {
        $config = [
            'events' => [
                'route'        => 'event_index',
                'child_routes' => [
                    'event_create',
                    'event_update_multi',
                    'event_update',
                ],
            ],
            'forms'  => [
                'route'        => 'form_index',
                'child_routes' => [
                    'form_create',
                    'form_show',
                    'form_update',
                ],
            ],
        ];

        $builder = $this->getBuilder();
        $config = $builder->build($config);
        $this->assertTrue($config['forms']['active'] ?? false);
        $this->assertFalse($config['events']['active'] ?? false);
    }

    /**
     * @return MenuConfigBuilder
     */
    private function getBuilder(): MenuConfigBuilder
    {
        $request = new Request();
        $request->attributes->set('_route', 'form_show');

        $stack = new RequestStack();
        $stack->push($request);

        $builder = new MenuConfigBuilder($stack, new ParameterBag());

        return $builder;
    }
}
