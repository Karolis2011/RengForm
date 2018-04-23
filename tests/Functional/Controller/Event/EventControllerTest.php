<?php

namespace App\Tests\Functional\Controller\Event;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\MultiEvent;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Tests\TestCases\DatabaseTestCase;

/**
 * Class EventControllerTest
 */
class EventControllerTest extends DatabaseTestCase
{
    /**
     * @return array
     */
    public function getTestIndexData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            null,
            500,
        ];

        //case #1
        $cases[] = [
            '09606e22-3851-11e8-9074-080027c702a7',
            200,
        ];

        //case #2
        $cases[] = [
            'ae9f01c4-38bb-11e8-9074-080027c702a7',
            200,
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestIndexData
     * @param null|string $eventId
     * @param int         $expected
     */
    public function testIndex(?string $eventId, int $expected)
    {
        $client = $this->getClient();
        $client->request('GET', '/en/event/' . $eventId);
        $response = $client->getResponse();
        $this->assertEquals($expected, $response->getStatusCode());
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
            Category::class,
            Workshop::class,
            WorkshopTime::class,
        ];

        return $classes;
    }

    /**
     * @return string
     */
    protected function getPathToFixture(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/event_controller_test_data.sql';
    }
}
