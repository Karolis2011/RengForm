<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FormField;

/**
 * Class NumberField
 */
class NumberField implements ValidatorInterface
{
    const TYPE = 'number';

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
        } elseif (!(is_null($value) || $value == '')) {
            if (!is_numeric($value)) {
                $errors[] = sprintf("%s must be a number", ucfirst($field->getLabel()));
            } else {
                if (!is_null($field->getMin()) && $field->getMin() > $value) {
                    $errors[] = sprintf(
                        "%s can not be lower than %d",
                        ucfirst($field->getLabel()),
                        $field->getMin()
                    );
                }

                if (!is_null($field->getMax()) && $field->getMax() < $value) {
                    $errors[] = sprintf(
                        "%s can not be higher than %d",
                        ucfirst($field->getLabel()),
                        $field->getMax()
                    );
                }
            }
        }

        return $errors;
    }
}
