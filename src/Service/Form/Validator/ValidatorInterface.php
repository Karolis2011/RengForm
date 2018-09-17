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
    public function validate(FormField $field, array $fieldData): array;
}
