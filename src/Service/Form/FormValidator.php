<?php

namespace App\Service\Form;

use App\Entity\EventTime;
use App\Entity\WorkshopTime;

/**
 * Class FormValidator
 */
class FormValidator
{
    /**
     * @param EventTime|WorkshopTime $time
     * @param array                  $formData
     * @return bool
     */
    public function validate($time, array $formData): bool
    {
        $valid = true;

        if (empty($formData)) {
            $valid = false;
        }
        //TODO: validate

        return $valid;
    }
}
