<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistrationRepository")
 */
class Registration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $data;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WorkshopTime", inversedBy="registrations")
     */
    private $workshopTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventTime", inversedBy="registrations")
     */
    private $eventTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $groupRegistration = false;

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
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return Registration
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @param \DateTimeInterface $created
     * @return Registration
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return WorkshopTime|null
     */
    public function getWorkshopTime(): ?WorkshopTime
    {
        return $this->workshopTime;
    }

    /**
     * @param WorkshopTime|null $workshopTime
     * @return Registration
     */
    public function setWorkshopTime(?WorkshopTime $workshopTime): self
    {
        $this->workshopTime = $workshopTime;

        return $this;
    }

    /**
     * @return EventTime|null
     */
    public function getEventTime(): ?EventTime
    {
        return $this->eventTime;
    }

    public function setEventTime(?EventTime $eventTime): self
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    /**
     * @return bool
     */
    public function getGroupRegistration(): bool
    {
        return $this->groupRegistration;
    }

    /**
     * @return bool
     */
    public function isGroupRegistration(): bool
    {
        return $this->getGroupRegistration();
    }

    /**
     * @param bool $groupRegistration
     * @return Registration
     */
    public function setGroupRegistration(bool $groupRegistration): self
    {
        $this->groupRegistration = $groupRegistration;

        return $this;
    }
}
