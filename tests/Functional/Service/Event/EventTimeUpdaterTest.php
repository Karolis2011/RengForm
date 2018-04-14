<?php

namespace App\Tests\Functional\Service\Event;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Registration;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Form\Model\EventTimeModel;
use App\Tests\TestCases\DatabaseTestCase;

class EventTimeUpdaterTest extends DatabaseTestCase
{
    public function testUpdateFail()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');
        try {
            $updater->update([], new FormConfig());
            $this->assertSame(0, 1, "Should have failed");
        } catch (\Exception $exception) {
            $this->assertSame(1, 1);
        }
    }

    /**
     * @throws \Exception
     */
    public function testUpdateEventTimes()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');

        $event = $this->getEntityManager()->getRepository(Event::class)->find('09606e22-3851-11e8-9074-080027c702a7');
        $eventTime = $this->getEntityManager()->getRepository(EventTime::class)
            ->find('367c42c6-3863-11e8-9074-080027c702a7');
        $newTimes = [];
        $newTimes[0] = new EventTimeModel($eventTime);
        $newTimes[0]->setStartTime(new \DateTime('2018-01-01 10:00'));
        $newTimes[1] = new EventTimeModel();
        $newTimes[1]->setStartTime(new \DateTime('2017-01-01 10:00'));
        $updater->update($newTimes, $event);

        $this->getEntityManager()->clear();
        $event = $this->getEntityManager()->getRepository(Event::class)->find('09606e22-3851-11e8-9074-080027c702a7');
        $this->assertCount(2, $event->getTimes());
        foreach ($event->getTimes() as $time) {
            if ($time->getId() == '367c42c6-3863-11e8-9074-080027c702a7') {
                $this->assertSame('2018-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            } else {
                $this->assertSame('2017-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function testUpdateWorkshopTimes()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');

        $event = $this->getEntityManager()->getRepository(Workshop::class)
            ->find('9b65a2ab-38c0-11e8-9074-080027c702a7');
        $newTimes = [];
        $newTimes[0] = new EventTimeModel();
        $newTimes[0]->setId('9b65be4a-38c0-11e8-9074-080027c702a7');
        $newTimes[0]->setStartTime(new \DateTime('2018-01-01 10:00'));
        $newTimes[1] = new EventTimeModel();
        $newTimes[1]->setStartTime(new \DateTime('2017-01-01 10:00'));
        $updater->update($newTimes, $event);

        $this->getEntityManager()->clear();
        $event = $this->getEntityManager()->getRepository(Workshop::class)
            ->find('9b65a2ab-38c0-11e8-9074-080027c702a7');
        $this->assertCount(2, $event->getTimes());
        foreach ($event->getTimes() as $time) {
            if ($time->getId() == '9b65be4a-38c0-11e8-9074-080027c702a7') {
                $this->assertSame('2018-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            } else {
                $this->assertSame('2017-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            }
        }
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
        ];

        return $classes;
    }

    /**
     * @return string
     */
    protected function getPathToFixture(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/event_time_updater_test_data.sql';
    }
}
