<?php

namespace App\Tests\Unit\Service\Export;

use App\Entity\Category;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Registration;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Service\Export\Exporter;
use PHPUnit\Framework\TestCase;

/**
 * Class ExporterTest
 */
class ExporterTest extends TestCase
{
    public function testExport()
    {

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
                        'name' => 'Aaa',
                        'age'  => '12',
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
        ]));
        $workshop->setFormConfig($config);
        $workshop->setCategory($category);
        $category->getWorkshops()->add($workshop);

        try {
            $response = (new Exporter())->export($event);
        } catch (\Exception $e) {
            $this->assertSame(1, 0, $e->getMessage());
            $response = '';
        }
        $expected = file_get_contents('fixtures/export.csv');
        $this->assertSame($expected, $response->getContent());
        try {
            (new Exporter())->export(new FormConfig());
            $this->assertSame(1, 0, 'Should have failed');
        } catch (\Exception $e) {
            $this->assertSame(1, 1);
        }
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
