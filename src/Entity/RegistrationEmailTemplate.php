<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistrationEmailTemplateRepository")
 */
class RegistrationEmailTemplate extends EmailTemplate
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\FormConfig", inversedBy="emailTemplate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formConfig;

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
