<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventTimeRepository")
 */
class EventTime
{
    /**
     * @ORM\Id()
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
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="eventTime", orphanRemoval=true)
     */
    private $registrations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="times")
     * @ORM\JoinColumn(nullable=false)
     * @var Event
     */
    private $event;

    /**
     * EventTime constructor.
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
     * @return EventTime
     */
    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
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
     * @return EventTime
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
     * @param Registration $registration
     * @return EventTime
     */
    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setEventTime($this);
        }

        return $this;
    }

    /**
     * @param Registration $registration
     * @return EventTime
     */
    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getEventTime() === $this) {
                $registration->setEventTime(null);
            }
        }

        return $this;
    }

    /**
     * @return Event|null
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * @param Event|null $event
     * @return EventTime
     */
    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntriesLeft()
    {
        return $this->event->getCapacity() - $this->entries;
    }

    /**
     * @param bool $isAdmin
     * @return bool
     */
    public function isAvailable(bool $isAdmin = false)
    {
        $haveOpenSlots = $this->getEvent()->getCapacity() === null || $this->getEvent()->getCapacity() > $this->entries;
        return $haveOpenSlots && (!$this->getEvent()->getIsOpen() || $isAdmin);
    }

    /**
     * @param bool $isAdmin
     * @return bool
     */
    public function getIsAvailable(bool $isAdmin = false)
    {
        return $this->isAvailable($isAdmin);
    }

    /**
     * @param int $count
     */
    public function increaseEntries(int $count = 1)
    {
        $this->entries += $count;
    }
}
