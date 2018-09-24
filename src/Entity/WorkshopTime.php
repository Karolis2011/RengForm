<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $startTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $entries = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="workshopTime", orphanRemoval=true)
     */
    private $registrations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workshop", inversedBy="times")
     * @ORM\JoinColumn(nullable=false)
     * @var Workshop
     */
    private $workshop;

    /**
     * WorkshopTime constructor.
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
     * @return \DateTimeInterface|null
     */
    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    /**
     * @param \DateTimeInterface $startTime
     * @return WorkshopTime
     */
    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->startTime->add((new \DateTime('00:00'))->diff($this->getWorkshop()->getDuration()));
    }

    /**
     * @return int|null
     */
    public function getEntries(): ?int
    {
        return $this->entries;
    }

    /**
     * @param int $entries
     * @return WorkshopTime
     */
    public function setEntries(int $entries): self
    {
        $this->entries = $entries;

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    /**
     * @return Workshop|null
     */
    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    /**
     * @param Workshop|null $workshop
     * @return WorkshopTime
     */
    public function setWorkshop(?Workshop $workshop): self
    {
        $this->workshop = $workshop;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntriesLeft()
    {
        return $this->workshop->getCapacity() - $this->entries;
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->workshop->getCapacity() === null || $this->workshop->getCapacity() > $this->entries;
    }

    /**
     * @param int $count
     */
    public function increaseEntries(int $count = 1)
    {
        $this->entries += $count;
    }
}
