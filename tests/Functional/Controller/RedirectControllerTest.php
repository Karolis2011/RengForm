<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class RedirectControllerTest
 */
class RedirectControllerTest extends WebTestCase
{
    /**
     * @return array
     */
    public function getTestRedirectActionData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            '/lt/',
            200,
            null
        ];

        //case #1
        $cases[] = [
            '/',
            302,
            '/lt/'
        ];

        //case #2
        $cases[] = [
            '',
            302,
            '/lt/'
        ];

        //case #3
        $cases[] = [
            '/asdf',
            302,
            '/lt/asdf'
        ];

        //case #4
        $cases[] = [
            '/lt/asdf',
            500,
            null
        ];

        //case #5
        $cases[] = [
            '/en/asdf',
            500,
            null
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestRedirectActionData
     * @param string      $url
     * @param int         $expected
     * @param string|null $redirect
     */
    public function testRedirectAction(string $url, int $expected, ?string $redirect)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $response = $client->getResponse();

        $this->assertEquals($expected, $response->getStatusCode());
        if ($redirect !== null) {
            /** @var RedirectResponse $response */
            $this->assertEquals($redirect, $response->getTargetUrl());
        }
    }
}
