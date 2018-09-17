<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailTemplateRepository")
 */
class EmailTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $receiverField;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\FormConfig", inversedBy="emailTemplate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formConfig;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getReceiverField(): ?string
    {
        return $this->receiverField;
    }

    public function setReceiverField(string $receiverField): self
    {
        $this->receiverField = $receiverField;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getFormConfig(): ?FormConfig
    {
        return $this->formConfig;
    }

    public function setFormConfig(FormConfig $formConfig): self
    {
        $this->formConfig = $formConfig;

        return $this;
    }
}
