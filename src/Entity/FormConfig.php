<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormConfigRepository")
 */
class FormConfig
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
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $config = '';

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="formConfigs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

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
     * @return FormConfig
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
     * @return FormConfig
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
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function getConfigParsed()
    {
        $parsed = json_decode($this->config, true);
        if ($parsed === null) {
            $parsed = [];
        }

        return $parsed;
    }

    /**
     * @param null|string $config
     * @return FormConfig
     */
    public function setConfig(?string $config): self
    {
        $this->config = '';
        if ($config !== null) {
            $this->config = $config;
        }

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
     * @return FormConfig
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
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
     * @return FormConfig
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
