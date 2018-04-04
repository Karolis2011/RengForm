<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FieldValue;
use App\Service\Form\FormField;

/**
 * Class CheckboxGroupField
 */
class CheckboxGroupField implements ValidatorInterface
{
    const TYPE = 'checkbox-group';

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
            if (is_array($values)) {
                $fieldValues = array_map(
                    function (FieldValue $value) {
                        return $value->getValue();
                    },
                    $field->getValues()
                );

                foreach ($values as $value) {
                    if (!in_array($value, $fieldValues)) {
                        $errors[] = sprintf(
                            "'%s' is not valid selection for %s",
                            $value,
                            ucfirst($field->getLabel())
                        );
                    }
                }
            }
        }

        return $errors;
    }
}
