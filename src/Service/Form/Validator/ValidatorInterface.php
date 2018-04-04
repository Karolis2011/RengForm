<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FormField;

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * @param FormField $field
     * @param array      $fieldData
     * @return array
     */
    public static function validate(FormField $field, array $fieldData): array;
}
