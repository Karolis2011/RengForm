<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FieldValue;
use App\Service\Form\FormField;

/**
 * Class CheckboxGroupField
 */
class SelectField implements ValidatorInterface
{
    const TYPE = 'select';

    /**
     * @param FormField $field
     * @param array     $fieldData
     * @return array
     */
    public function validate(FormField $field, array $fieldData): array
    {
        $errors = [];
        $values = $fieldData[$field->getName()] ?? null;

        if ($field->isRequired() && $this->isEmpty($field, $values)) {
            $errors[] = sprintf(
                "%s is required",
                ucfirst($field->getLabel())
            );
        } elseif (!$this->isEmpty($field, $values)) {
            $fieldValues = array_map(
                function (FieldValue $value) {
                    return $value->getValue();
                },
                $field->getValues()
            );
            if (is_array($values)) {
                if (!$field->isMultiple()) {
                    $errors[] = sprintf(
                        "Only one value can be chosen for %s",
                        ucfirst($field->getLabel())
                    );
                } else {
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
            } else {
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

    /**
     * @param FormField $field
     * @param           $values
     * @return bool
     */
    public function isEmpty(FormField $field, $values): bool
    {
        $empty = false;

        if (is_null($values)
            || $values == ''
            || (is_array($values)
                && (empty($values)
                    || ($field->isMultiple()
                        && count($values) == 1
                        && (is_null($values[0])
                            || $values[0] == ''
                        )
                    )
                )
            )
        ) {
            $empty = true;
        }

        return $empty;
    }
}
