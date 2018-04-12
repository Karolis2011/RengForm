<?php

namespace App\Service\Event;

use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Form\Model\EventTimeModel;
use App\Repository\EventTimeRepository;
use App\Repository\WorkshopTimeRepository;

/**
 * Class EventTimeUpdate
 */
class EventTimeUpdater
{
    /**
     * @var EventTimeRepository
     */
    private $eventTimeRepository;

    /**
     * @var WorkshopTimeRepository
     */
    private $workshopTimeRepository;

    /**
     * EventTimeUpdate constructor.
     * @param EventTimeRepository    $eventTimeRepository
     * @param WorkshopTimeRepository $workshopTimeRepo
     */
    public function __construct(
        EventTimeRepository $eventTimeRepository,
        WorkshopTimeRepository $workshopTimeRepo
    ) {
        $this->eventTimeRepository = $eventTimeRepository;
        $this->workshopTimeRepository = $workshopTimeRepo;
    }

    /**
     * @param EventTimeModel[] $formTimes
     * @param Event|Workshop   $event
     * @throws \Exception
     */
    public function update(array $formTimes, $event): void
    {
        $repository = $this->getRepository(get_class($event));

        $oldTimes = [];
        $newTimes = [];
        foreach ($formTimes as $time) {
            if ($time->getId() === null) {
                $newTimes[] = $time;
            } else {
                $oldTimes[$time->getId()] = $time;
            }
        }

        foreach ($event->getTimes() as $time) {
            if (isset($oldTimes[$time->getId()])) {
                $time->setStartTime($oldTimes[$time->getId()]->getStartTime());
                $repository->update($time);
            } else {
                $event->getTimes()->removeElement($time);
                $repository->remove($time);
            }
        }

        foreach ($newTimes as $time) {
            $eventTime = $this->getNewTimeObject($event);
            $eventTime->setStartTime($time->getStartTime());
            $event->getTimes()->add($eventTime);
            $repository->save($eventTime);
        }
    }

    /**
     * @param Event|Workshop $event
     * @return EventTime|WorkshopTime
     * @throws \Exception
     */
    private function getNewTimeObject($event)
    {
        switch (get_class($event)) {
            case Event::class:
                $eventTime = new EventTime();
                $eventTime->setEvent($event);

                return $eventTime;
            case Workshop::class:
                $workshopTime = new WorkshopTime();
                $workshopTime->setWorkshop($event);

                return $workshopTime;
            default:
                throw new \Exception(sprintf("Class not supported: %s", get_class($event)));
        }
    }

    /**
     * @param string $class
     * @return EventTimeRepository|WorkshopTimeRepository
     * @throws \Exception
     */
    private function getRepository(string $class)
    {
        switch ($class) {
            case Event::class:
                return $this->eventTimeRepository;
            case Workshop::class:
                return $this->workshopTimeRepository;
            default:
                throw new \Exception(sprintf("Class not supported: %s", $class));
        }
    }
}
