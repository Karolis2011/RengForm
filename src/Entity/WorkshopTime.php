<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkshopTimeRepository")
 */
class WorkshopTime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $startTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $entries = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workshop", inversedBy="times")
     * @ORM\JoinColumn(name="workshopId")
     * @var Workshop $workshop
     */
    private $workshop;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="workshopTime")
     */
    private $registrations;

    /**
     * Workshop constructor.
     */
    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param mixed $entries
     */
    public function setEntries($entries): void
    {
        $this->entries = $entries;
    }

    /**
     * @param int $count
     */
    public function increaseEntries(int $count = 1)
    {
        $this->entries += $count;
    }

    /**
     * @return Workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * @param Workshop $workshop
     */
    public function setWorkshop($workshop): void
    {
        $this->workshop = $workshop;
    }

    /**
     * @return mixed
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * @return int
     */
    public function getEntriesLeft()
    {
        return $this->workshop->getCapacity() - $this->entries;
    }

    public function isAvailable()
    {
        return $this->workshop->getCapacity() === null || $this->workshop->getCapacity() > $this->entries;
    }
}
