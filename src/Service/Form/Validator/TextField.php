<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FormField;

/**
 * Class TextField
 */
class TextField implements ValidatorInterface
{
    const TYPE = 'text';

    /**
     * @param FormField $field
     * @param array      $fieldData
     * @return array
     */
    public static function validate(FormField $field, array $fieldData): array
    {
        $errors = [];
        $value = $fieldData[$field->getName()] ?? null;

        if ($field->isRequired() && (is_null($value) || $value == '')) {
            $errors[] = sprintf("%s is required", ucfirst($field->getLabel()));
        } elseif (!is_null($field->getMaxLength()) && $field->getMaxLength() < strlen($value)) {
            $errors[] = sprintf(
                "%s can not be longer than %d characters",
                ucfirst($field->getLabel()),
                $field->getMaxLength()
            );
        }

        return $errors;
    }
}
