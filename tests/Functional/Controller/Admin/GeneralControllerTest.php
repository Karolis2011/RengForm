<?php

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Category;
use App\Entity\EmailTemplate;
use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Registration;
use App\Entity\User;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Tests\TestCases\DatabaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class GeneralControllerTest
 * Purpose of this test is to check if all pages load successfully
 */
class GeneralControllerTest extends DatabaseTestCase
{
    /**
     * @return array
     */
    public function getTestUrlNotLoggedInData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            '/en/admin/login',
            200,
        ];

        //case #1
        $cases[] = [
            '/en/admin/register',
            200,
        ];

        //case #2
        $cases[] = [
            '/en/admin/logout',
            302,
        ];

        //case #3
        $cases[] = [
            '/en/admin/event/',
            302,
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestUrlNotLoggedInData
     * @param string $url
     * @param int    $expected
     */
    public function testUrlNotLoggedIn(string $url, int $expected)
    {
        $client = $this->getClient();
        $client->request('GET', $url);
        $this->assertEquals($expected, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function getTestUrlLoggedInData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            '/en/admin/event/',
            200,
        ];

        //case #1
        $cases[] = [
            '/en/admin/event/create',
            200,
        ];

        //case #2
        $cases[] = [
            '/en/admin/event/create_multi',
            200,
        ];

        //case #3
        //Single event
        $cases[] = [
            '/en/admin/event/09606e22-3851-11e8-9074-080027c702a7',
            200,
        ];

        //case #4
        //Single event
        $cases[] = [
            '/en/admin/event/09606e22-3851-11e8-9074-080027c702a7/update',
            200,
        ];

        //case #5
        //Single event
        $cases[] = [
            '/en/admin/event/09606e22-3851-11e8-9074-080027c702a7/times/update',
            200,
        ];

        //case #6
        //Single event
        $cases[] = [
            '/en/admin/event/09606e22-3851-11e8-9074-080027c702a7/delete',
            302,
        ];

        //case #7
        //Multi event
        $cases[] = [
            '/en/admin/event/ae9f01c4-38bb-11e8-9074-080027c702a7',
            200,
        ];

        //case #8
        //Multi event
        $cases[] = [
            '/en/admin/event/ae9f01c4-38bb-11e8-9074-080027c702a7/update_multi',
            200,
        ];

        //case #9
        //Multi event
        $cases[] = [
            '/en/admin/event/ae9f01c4-38bb-11e8-9074-080027c702a7/category/create',
            200,
        ];

        //case #10
        //Multi event
        $cases[] = [
            '/en/admin/event/ae9f01c4-38bb-11e8-9074-080027c702a7/workshop/create',
            200,
        ];

        //case #11
        //Multi event
        $cases[] = [
            '/en/admin/event/ae9f01c4-38bb-11e8-9074-080027c702a7/delete',
            302,
        ];

        //case #12
        $cases[] = [
            '/en/admin/form/',
            200,
        ];

        //case #13
        $cases[] = [
            '/en/admin/form/create',
            200,
        ];

        //case #14
        $cases[] = [
            '/en/admin/form/ebe13752-384c-11e8-9074-080027c702a7',
            200,
        ];

        //case #15
        $cases[] = [
            '/en/admin/form/ebe13752-384c-11e8-9074-080027c702a7/update',
            200,
        ];

        //case #16
        $cases[] = [
            '/en/admin/form/ebe13752-384c-11e8-9074-080027c702a7/delete',
            302,
        ];

        //case #17
        $cases[] = [
            '/en/admin/profile',
            200,
        ];

        //case #18
        $cases[] = [
            '/en/admin/change_email',
            302,
        ];

        //case #19
        $cases[] = [
            '/en/admin/category/89cacd61-38c0-11e8-9074-080027c702a7/update',
            200,
        ];

        //case #20
        $cases[] = [
            '/en/admin/category/89cacd61-38c0-11e8-9074-080027c702a7/delete',
            302,
        ];

        //case #21
        $cases[] = [
            '/en/admin/workshop/9b65a2ab-38c0-11e8-9074-080027c702a7/update',
            200,
        ];

        //case #22
        $cases[] = [
            '/en/admin/workshop/9b65a2ab-38c0-11e8-9074-080027c702a7/delete',
            302,
        ];

        //case #23
        $cases[] = [
            '/en/admin/workshop/9b65a2ab-38c0-11e8-9074-080027c702a7/registrations',
            200,
        ];

        //case #24
        $cases[] = [
            '/en/admin/workshop/9b65a2ab-38c0-11e8-9074-080027c702a7/times/update',
            200,
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestUrlLoggedInData
     * @param string $url
     * @param int    $expected
     */
    public function testUrlLoggedIn(string $url, int $expected)
    {
        $this->logIn();
        $client = $this->getClient();
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertEquals($expected, $response->getStatusCode(), $response->getContent());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'main';

        $token = new UsernamePasswordToken('admin', 'admin', $firewallContext, ['ROLE_USER']);
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * @return array
     */
    protected function getClasses(): array
    {
        $classes = [
            MultiEvent::class,
            Event::class,
            EventTime::class,
            Workshop::class,
            WorkshopTime::class,
            FormConfig::class,
            Registration::class,
            Category::class,
            User::class,
            EmailTemplate::class,
        ];

        return $classes;
    }

    /**
     * @return string
     */
    protected function getPathToFixture(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/general_controller_test_data.sql';
    }
}
