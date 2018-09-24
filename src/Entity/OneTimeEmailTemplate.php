<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OneTimeEmailTemplateRepository")
 */
class OneTimeEmailTemplate extends EmailTemplate
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     * @ORM\JoinColumn(nullable=true)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workshop")
     * @ORM\JoinColumn(nullable=true)
     */
    private $workshop;

    /**
     * @return Event|null
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     * @return OneTimeEmailTemplate
     */
    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Workshop|null
     */
    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    /**
     * @param Workshop $workshop
     * @return OneTimeEmailTemplate
     */
    public function setWorkshop(Workshop $workshop): self
    {
        $this->workshop = $workshop;

        return $this;
    }
}
