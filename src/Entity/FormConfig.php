<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormConfigRepository")
 */
class FormConfig
{
    const SIMPLE = 'simple';
    const GROUP = 'group';

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
     * @Assert\NotBlank(message="Form is empty")
     * @Assert\NotEqualTo(value="[]", message="Form is empty")
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
     * @ORM\Column(type="string", length=255)
     */
    private $type = self::SIMPLE;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\EmailTemplate", mappedBy="formConfig", cascade={"persist", "remove"})
     */
    private $emailTemplate;

    /**
     * @return string
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return EmailTemplate|null
     */
    public function getEmailTemplate(): ?EmailTemplate
    {
        return $this->emailTemplate;
    }

    /**
     * @param EmailTemplate|null $emailTemplate
     * @return FormConfig
     */
    public function setEmailTemplate(?EmailTemplate $emailTemplate): self
    {
        $this->emailTemplate = $emailTemplate;

        // set the owning side of the relation if necessary
        if ($emailTemplate !== null && $this !== $emailTemplate->getFormConfig()) {
            $emailTemplate->setFormConfig($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFieldNames(): array
    {
        $names = [];

        foreach ($this->getConfigParsed() as $field) {
            $names[$field['label']] = $field['name'];
        }

        return $names;
    }
}
