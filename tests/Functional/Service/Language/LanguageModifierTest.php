<?php

namespace App\Tests\Functional\Service\Language;

use App\Service\Language\LanguageModifier;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class LanguageModifierTest
 */
class LanguageModifierTest extends WebTestCase
{
    /**
     * @return array
     */
    public function getTestGetUrlData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            'form_show',
            [
                '_locale' => 'en',
                'formId'  => '904e029c-30a2-11e8-90c6-080027c702a7',
            ],
            '/lt/admin/form/904e029c-30a2-11e8-90c6-080027c702a7',
            '/en/admin/form/904e029c-30a2-11e8-90c6-080027c702a7',
        ];

        //case #1
        $cases[] = [
            'event',
            [
                '_locale' => 'en',
                'eventId'  => '904e029c-30a2-11e8-90c6-080027c702a7',
            ],
            '/lt/event/904e029c-30a2-11e8-90c6-080027c702a7',
            '/en/event/904e029c-30a2-11e8-90c6-080027c702a7',
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestGetUrlData
     * @param string $route
     * @param array  $routeParams
     * @param string $expectedLt
     * @param string $expectedEn
     */
    public function testGetUrl(string $route, array $routeParams, string $expectedLt, string $expectedEn)
    {
        $modifier = $this->getModifier($route, $routeParams);

        $ltUrl = $modifier->getUrl('lt');
        $enUrl = $modifier->getUrl('en');

        $this->assertEquals($expectedLt, $ltUrl);
        $this->assertEquals($expectedEn, $enUrl);
    }

    /**
     * @param string $route
     * @param array  $routeParams
     * @return LanguageModifier
     */
    private function getModifier(string $route, array $routeParams): LanguageModifier
    {
        $request = new Request();
        $request->attributes->set('_route', $route);
        $request->attributes->set('_route_params', $routeParams);

        $stack = new RequestStack();
        $stack->push($request);

        $generator = $this->createClient()->getContainer()->get('test.url_generator');
        $modifier = new LanguageModifier($stack, $generator);

        return $modifier;
    }
}
