<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FieldValue;
use App\Service\Form\FormField;

/**
 * Class RadioGroupField
 */
class RadioGroupField implements ValidatorInterface
{
    const TYPE = 'radio-group';

    /**
     * @param FormField $field
     * @param array     $fieldData
     * @return array
     */
    public static function validate(FormField $field, array $fieldData): array
    {
        $errors = [];
        $values = $fieldData[$field->getName()] ?? null;

        if ($field->isRequired() && (is_null($values) || empty($values))) {
            $errors[] = sprintf(
                "%s is required",
                ucfirst($field->getLabel())
            );
        } elseif (!(is_null($values) || empty($values))) {
            if (is_array($values) && count($values) > 1) {
                $errors[] = sprintf(
                    "Only 1 option can be selected for %s",
                    ucfirst($field->getLabel())
                );
            } else {
                $values = is_array($values) ? $values[0] : $values;

                $fieldValues = array_map(
                    function (FieldValue $value) {
                        return $value->getValue();
                    },
                    $field->getValues()
                );

                if (!in_array($values, $fieldValues)) {
                    $errors[] = sprintf(
                        "'%s' is not valid selection for %s",
                        $values,
                        ucfirst($field->getLabel())
                    );
                }
            }
        }

        return $errors;
    }
}
