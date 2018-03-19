<?php

namespace App\Service\Form;

/**
 * Class FormField
 */
class FormField
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var int
     */
    private $maxLength;

    /**
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * @var bool
     */
    private $toggle;

    /**
     * @var bool
     */
    private $multiple;

    /**
     * FormField constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->type = $config['type'] ?? '';
        $this->label = $config['label'] ?? '';
        $this->name = $config['name'] ?? '';
        $this->values = $this->parseValues($config['values'] ?? []);
        $this->value = $config['value'] ?? '';
        $this->required = $config['required'] ?? false;
        $this->description = $config['description'] ?? '';
        $this->placeholder = $config['placeholder'] ?? '';
        $this->maxLength = $config['maxlength'] ?? null;
        $this->min = $config['min'] ?? null;
        $this->max = $config['max'] ?? null;
        $this->toggle = $config['toggle'] ?? false;
        $this->multiple = $config['multiple'] ?? false;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
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
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @return int|null
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @return bool
     */
    public function isToggle(): bool
    {
        return $this->toggle;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param array $values
     * @return array
     */
    private function parseValues(array $values)
    {
        $parsedValues = [];

        foreach ($values as $value) {
            $parsedValues[] = new FieldValue($value);
        }

        return $parsedValues;
    }
}
