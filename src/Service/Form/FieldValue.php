<?php

namespace App\Service\Form;

/**
 * Class FieldValue
 */
class FieldValue
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $selected;

    /**
     * FieldValue constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->label = $config['label'] ?? '';
        $this->value = $config['value'] ?? '';
        $this->selected = $config['selected'] ?? false;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }
}
