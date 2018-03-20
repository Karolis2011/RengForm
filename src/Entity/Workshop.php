<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkshopRepository")
 */
class Workshop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @ORM\Column(type="json_array")
     */
    private $startTimes = [];

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\Column(type="integer")
     */
    private $entries = 0;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="workshops")
     * @ORM\JoinColumn(name="categoryId")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormConfig")
     * @ORM\JoinColumn(name="formConfigId")
     */
    private $formConfig;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="workshop")
     */
    private $registrations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location")
     * @ORM\JoinColumn(name="locationId")
     */
    private $location;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place): void
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getStartTimes()
    {
        if (is_array($this->startTimes)) {
            foreach ($this->startTimes as &$time) {
                if (is_array($time)) {
                    $time = new \DateTime($time['date'], new \DateTimeZone($time['timezone']));
                }
            }
        }

        return $this->startTimes;
    }

    /**
     * @param mixed $startTimes
     */
    public function setStartTimes($startTimes): void
    {
        $this->startTimes = $startTimes;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity): void
    {
        $this->capacity = $capacity;
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
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return FormConfig
     */
    public function getFormConfig()
    {
        return $this->formConfig;
    }

    /**
     * @param FormConfig $formConfig
     */
    public function setFormConfig($formConfig): void
    {
        $this->formConfig = $formConfig;
    }

    /**
     * @return mixed
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @param int $count
     */
    public function increaseEntries(int $count = 1)
    {
        $this->entries += $count;
    }
}
