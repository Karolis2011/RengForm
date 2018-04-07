<?php

namespace App\Form\Model;

use App\Entity\EventTime;

/**
 * Class EventTimeModel
 */
class EventTimeModel
{
    /**
     * @var null|string
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * EventTimeModel constructor.
     * @param EventTime|null $eventTime
     */
    public function __construct(?EventTime $eventTime = null)
    {
        if ($eventTime !== null) {
            $this->id = $eventTime->getId();
            $this->startTime = $eventTime->getStartTime();
        }
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $startTime
     */
    public function setStartTime(\DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }
}
