<?php

namespace App\Tests\Unit\Service\Export;

use App\Entity\Category;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Registration;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Service\Export\Parser\MultiEventParser;
use PHPUnit\Framework\TestCase;

/**
 * Class MultiEventParserTest
 */
class MultiEventParserTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestExportData(): array
    {
        $cases = [];

        //case #0
        $event = new MultiEvent();
        $event->setTitle('Multi Event Title');
        $cases[] = [
            $event,
            [
                [
                    'MultiEvent',
                    'Multi Event Title',
                ],
            ],
        ];

        //case #1
        $cases[] = [
            new Category(),
            [],
            true,
        ];

        //case #2
        $event = new MultiEvent();
        $event->setTitle('Multi Event Title');
        $category = new Category();
        $category->setTitle('Category Title');
        $event->getCategories()->add($category);
        $category->setEvent($event);
        $category = new Category();
        $category->setTitle('Category2 Title');
        $event->getCategories()->add($category);
        $category->setEvent($event);
        $cases[] = [
            $event,
            [
                [
                    'MultiEvent',
                    'Multi Event Title',
                ],
                [],
                [
                    'Category',
                    'Category Title',
                ],
                ['No workshops in category'],
                [],
                [
                    'Category',
                    'Category2 Title',
                ],
                ['No workshops in category'],
            ],
        ];

        //case #3
        $event = new MultiEvent();
        $event->setTitle('Multi Event Title');
        $category = new Category();
        $category->setTitle('Category Title');
        $event->getCategories()->add($category);
        $category->setEvent($event);
        $workshop = new Workshop();
        $workshop->setCategory($category);
        $category->getWorkshops()->add($workshop);
        $workshop = new Workshop();
        $workshop->setCategory($category);
        $category->getWorkshops()->add($workshop);
        $cases[] = [
            $event,
            [
                [
                    'MultiEvent',
                    'Multi Event Title',
                ],
                [],
                [
                    'Category',
                    'Category Title',
                ],
                ['No times set for workshop'],
                ['No times set for workshop'],
            ],
        ];

        //case #4
        $event = new MultiEvent();
        $event->setTitle('Multi Event Title');
        $category = new Category();
        $category->setTitle('Category Title');
        $event->getCategories()->add($category);
        $category->setEvent($event);
        $workshop = new Workshop();
        $workshop->setTitle('Workshop Title');
        $this->addWorkshopTime($workshop, '2018-01-01 12:00', []);
        $this->addWorkshopTime($workshop, '2018-01-01 15:00', []);
        $workshop->setCategory($category);
        $category->getWorkshops()->add($workshop);
        $cases[] = [
            $event,
            [
                [
                    'MultiEvent',
                    'Multi Event Title',
                ],
                [],
                [
                    'Category',
                    'Category Title',
                ],
                [],
                [
                    'Workshop',
                    'Workshop Title',
                    '2018-01-01 12:00',
                ],
                ['No registrations in workshop'],
                [],
                [
                    'Workshop',
                    'Workshop Title',
                    '2018-01-01 15:00',
                ],
                ['No registrations in workshop'],
            ],
        ];

        //case #5
        $event = new MultiEvent();
        $event->setTitle('Multi Event Title');
        $category = new Category();
        $category->setTitle('Category Title');
        $event->getCategories()->add($category);
        $category->setEvent($event);
        $workshop = new Workshop();
        $workshop->setTitle('Workshop Title');
        $this->addWorkshopTime(
            $workshop,
            '2018-01-01 12:00',
            [
                [
                    'created' => '2017-12-20 12:34',
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
        $workshop->setFormConfig($config);
        $workshop->setCategory($category);
        $category->getWorkshops()->add($workshop);
        $cases[] = [
            $event,
            [
                [
                    'MultiEvent',
                    'Multi Event Title',
                ],
                [],
                [
                    'Category',
                    'Category Title',
                ],
                [],
                [
                    'Workshop',
                    'Workshop Title',
                    '2018-01-01 12:00',
                ],
                [],
                ['Single registrations'],
                [
                    'Registration Date',
                    'Name',
                    'Age',
                    'Select',
                ],
                [
                    '2017-12-20 12:34',
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
     * @param MultiEvent $object
     * @param array      $expected
     * @param bool       $expectedException
     */
    public function testExport($object, array $expected, $expectedException = false)
    {
        try {
            $result = MultiEventParser::parse($object);
        } catch (\Exception $e) {
            $this->assertSame(true, $expectedException, $e->getMessage());
            $result = [];
        }

        $this->assertEquals($expected, $result);
    }

    /**
     * @param Workshop $workshop
     * @param string   $startTime
     * @param array    $registrations
     */
    private function addWorkshopTime(Workshop $workshop, string $startTime, array $registrations): void
    {
        $workshopTime = new WorkshopTime();
        $workshopTime->setStartTime(new \DateTime($startTime));

        foreach ($registrations as $registration) {
            $reg = new Registration();
            $reg->setCreated(new \DateTime($registration['created']));
            $reg->setData($registration['data']);
            $reg->setWorkshopTime($workshopTime);
            $workshopTime->getRegistrations()->add($reg);
        }

        $workshopTime->setWorkshop($workshop);
        $workshop->getTimes()->add($workshopTime);
    }
}
