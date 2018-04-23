<?php

namespace App\Tests\Functional\Controller\Event;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class IndexControllerTest
 */
class IndexControllerTest extends WebTestCase
{
    /**
     * @return array
     */
    public function getTestIndexData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            '/en/',
            200,
            null,
        ];

        //case #1
        $cases[] = [
            '/lt/',
            200,
            null,
        ];

        //case #2
        $cases[] = [
            '/en',
            301,
            'http://localhost/en/',
        ];

        //case #3
        $cases[] = [
            '/lt',
            301,
            'http://localhost/lt/',
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestIndexData
     * @param string      $url
     * @param int         $expected
     * @param string|null $redirect
     */
    public function testIndex(string $url, int $expected, ?string $redirect)
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
