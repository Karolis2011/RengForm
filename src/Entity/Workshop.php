<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkshopRepository")
 */
class Workshop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description = '';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $place;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkshopTime", mappedBy="workshop", orphanRemoval=true, cascade={"persist"})
     * @Assert\Count(min="1", minMessage="At least 1 time has to be added")
     * @Assert\Valid()
     */
    private $times;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormConfig")
     */
    private $formConfig;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormConfig")
     */
    private $groupFormConfig;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="workshops")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOpen;

    /**
     * Workshop constructor.
     */
    public function __construct()
    {
        $this->times = new ArrayCollection();
        $this->duration = new \DateTime('01:00');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Workshop
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return Workshop
     */
    public function setDescription(?string $description): self
    {
        $this->description = '';
        if ($description !== null) {
            $this->description = $description;
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPlace(): ?string
    {
        return $this->place;
    }

    /**
     * @param string $place
     * @return Workshop
     */
    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    /**
     * @param \DateTimeInterface $duration
     * @return Workshop
     */
    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    /**
     * @param int|null $capacity
     * @return Workshop
     */
    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

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
     * @return Workshop
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|WorkshopTime[]
     */
    public function getTimes(): Collection
    {
        return $this->times;
    }

    /**
     * @return FormConfig|null
     */
    public function getFormConfig(): ?FormConfig
    {
        return $this->formConfig;
    }

    /**
     * @param FormConfig|null $formConfig
     * @return Workshop
     */
    public function setFormConfig(?FormConfig $formConfig): self
    {
        $this->formConfig = $formConfig;

        return $this;
    }

    /**
     * @return FormConfig|null
     */
    public function getGroupFormConfig(): ?FormConfig
    {
        return $this->groupFormConfig;
    }

    /**
     * @param FormConfig|null $groupFormConfig
     * @return Event
     */
    public function setGroupFormConfig(?FormConfig $groupFormConfig): self
    {
        $this->groupFormConfig = $groupFormConfig;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return Workshop
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param                           $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->formConfig === null && $this->groupFormConfig === null) {
            $context->buildViolation('Workshop must have at least one type of registration form set')
                ->atPath('formConfig')
                ->addViolation();
        }
    }

    public function getIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }
}
