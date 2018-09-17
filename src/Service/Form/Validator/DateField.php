<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FormField;

/**
 * Class DateField
 */
class DateField implements ValidatorInterface
{
    const TYPE = 'date';

    /**
     * @param FormField $field
     * @param array      $fieldData
     * @return array
     */
    public function validate(FormField $field, array $fieldData): array
    {
        $errors = [];
        $value = $fieldData[$field->getName()] ?? null;

        if ($field->isRequired() && (is_null($value) || $value == '')) {
            $errors[] = sprintf("%s is required", ucfirst($field->getLabel()));
        } elseif (!(is_null($value) || $value == '') && !self::validateDate($value)) {
            $errors[] = sprintf(
                "%s format is not valid, must be YYYY-MM-DD",
                ucfirst($field->getLabel())
            );
        }

        return $errors;
    }

    /**
     * @param string $date
     * @param string $format
     * @return bool
     */
    public function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $dateObj = \DateTime::createFromFormat($format, $date);

        return $dateObj && $dateObj->format($format) == $date;
    }
}
