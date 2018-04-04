<?php

namespace App\Service\Form;

/**
 * Class Form
 */
class Form
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * Form constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $field) {
            $this->fields[] = new FormField($field);
        }
    }

    /**
     * @return FormField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
