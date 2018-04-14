<?php

namespace App\Tests\Unit\Service\Theme;

use App\Service\Theme\MenuConfigBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MenuConfigBuilderTest
 */
class MenuConfigBuilderTest extends TestCase
{
    public function testBuild()
    {
        $builder = $this->getBuilder();
        $config = $builder->build();
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

        $builder = new MenuConfigBuilder($stack);

        return $builder;
    }
}
