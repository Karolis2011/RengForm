<?php

namespace App\Form\Model;

use App\Entity\EventTime;
use App\Entity\WorkshopTime;

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
     * @param EventTime|WorkshopTime|null $time
     */
    public function __construct($time = null)
    {
        if ($time !== null) {
            $this->id = $time->getId();
            $this->startTime = $time->getStartTime();
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
