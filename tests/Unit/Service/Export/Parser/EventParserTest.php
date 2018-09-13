<?php

namespace App\Tests\Unit\Service\Export;

use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
use App\Entity\Registration;
use App\Service\Export\Parser\EventParser;
use PHPUnit\Framework\TestCase;

/**
 * Class EventParserTest
 */
class EventParserTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestExportData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            new Event(),
            [
                ['No times set for event'],
            ],
        ];

        //case #1
        $cases[] = [
            new EventTime(),
            [],
            true,
        ];

        //case #2
        $event = new Event();
        $this->addEventTime($event, '2018-01-10 10:00', []);
        $this->addEventTime($event, '2018-01-10 12:00', []);
        $event->setTitle('Event Title');

        $cases[] = [
            $event,
            [
                [],
                [
                    'Event',
                    'Event Title',
                    '2018-01-10 10:00',
                ],
                [
                    'No registrations in event',
                ],
                [],
                [
                    'Event',
                    'Event Title',
                    '2018-01-10 12:00',
                ],
                [
                    'No registrations in event',
                ],
            ],
        ];

        //case #3
        $event = new Event();
        $this->addEventTime(
            $event,
            '2018-01-10 10:00',
            [
                [
                    'created' => '2017-12-20 12:24',
                    'data'    => [
                        'name'   => 'Aaa',
                        'age'    => '12',
                        'select' => [
                            'aa',
                            'bb',
                        ],
                    ],
                ],
            ]
        );
        $event->setTitle('Event Title');
        $config = new FormConfig();
        $config->setConfig(json_encode([
            [
                'type'  => 'text',
                'label' => 'Name',
                'name'  => 'name',
            ],
            [
                'type'  => 'number',
                'label' => 'Age',
                'name'  => 'age',
            ],
            [
                'type'  => 'select',
                'label' => 'Select',
                'name'  => 'select',
            ],
        ]));
        $event->setFormConfig($config);

        $cases[] = [
            $event,
            [
                [],
                [
                    'Event',
                    'Event Title',
                    '2018-01-10 10:00',
                ],
                [
                    'Single registrations',
                ],
                [
                    'Registration Date',
                    'Name',
                    'Age',
                    'Select',
                ],
                [
                    '2017-12-20 12:24',
                    'Aaa',
                    '12',
                    'aa, bb',
                ],
            ],
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestExportData
     * @param Event $object
     * @param array $expected
     * @param bool  $expectedException
     */
    public function testExport($object, array $expected, $expectedException = false)
    {
        try {
            $result = EventParser::parse($object);
        } catch (\Exception $e) {
            $this->assertSame(true, $expectedException, $e->getMessage());
            $result = [];
        }

        $this->assertEquals($expected, $result);
    }

    private function addEventTime(Event $event, string $startTime, array $registrations): void
    {
        $eventTime = new EventTime();
        $eventTime->setStartTime(new \DateTime($startTime));

        foreach ($registrations as $registration) {
            $reg = new Registration();
            $reg->setCreated(new \DateTime($registration['created']));
            $reg->setData($registration['data']);
            $reg->setEventTime($eventTime);
            $eventTime->getRegistrations()->add($reg);
        }

        $eventTime->setEvent($event);
        $event->getTimes()->add($eventTime);
    }
}
