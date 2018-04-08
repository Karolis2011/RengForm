<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MultiEventRepository")
 */
class MultiEvent
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
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $place;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="event", orphanRemoval=true)
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="multiEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * MultiEvent constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * @return MultiEvent
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
     * @return MultiEvent
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
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return MultiEvent
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface|null $endDate
     * @return MultiEvent
     */
    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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
     * @return MultiEvent
     */
    public function setPlace(string $place): self
    {
        $this->place = $place;

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
     * @return MultiEvent
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User|null $owner
     * @return MultiEvent
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $empty = $this->categories->isEmpty();

        if (!$empty) {
            $empty = true;

            /** @var Category $category */
            foreach ($this->categories as $category) {
                $empty = $empty && $category->getWorkshops()->isEmpty();
            }
        }

        return $empty;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param                           $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->endDate !== null && $this->date > $this->endDate) {
            $context->buildViolation('Event end time can not be before start time')
                ->atPath('endDate')
                ->addViolation();
        }
    }
}
