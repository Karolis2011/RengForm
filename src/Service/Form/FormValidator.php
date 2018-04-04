<?php

namespace App\Service\Form;

use App\Entity\EventTime;
use App\Entity\WorkshopTime;

/**
 * Class FormValidator
 */
class FormValidator
{
    const VALIDATORS = [
        Validator\TextField::TYPE          => Validator\TextField::class,
        Validator\DateField::TYPE          => Validator\DateField::class,
        Validator\SelectField::TYPE        => Validator\SelectField::class,
        Validator\NumberField::TYPE        => Validator\NumberField::class,
        Validator\TextAreaField::TYPE      => Validator\TextAreaField::class,
        Validator\RadioGroupField::TYPE    => Validator\RadioGroupField::class,
        Validator\CheckboxGroupField::TYPE => Validator\CheckboxGroupField::class,
    ];

    /**
     * @param EventTime|WorkshopTime $time
     * @param array                  $formData
     * @return bool
     * @throws \Exception
     */
    public function validate($time, array $formData): bool
    {
        if (empty($formData)) {
            return false;
        }

        $form = $this->getFormConfig($time);
        $errors = [];

        foreach ($form->getFields() as $field) {
            $validator = $this->getValidator($field->getType());
            $errors = array_merge($errors, $validator->validate($field, $formData));
        }

        return empty($errors);
    }

    /**
     * @param string $type
     * @return Validator\ValidatorInterface
     * @throws \Exception
     */
    private function getValidator(string $type): Validator\ValidatorInterface
    {
        if (!isset(self::VALIDATORS[$type])) {
            throw new \Exception(sprintf('Validator for type %s not found', $type));
        }

        $validator = self::VALIDATORS[$type];
        $validator = new $validator();

        return $validator;
    }

    /**
     * @param EventTime|WorkshopTime $time
     * @return Form
     * @throws \Exception
     */
    private function getFormConfig($time): Form
    {
        switch (get_class($time)) {
            case EventTime::class:
                $formConfig = $time->getEvent()->getFormConfig();
                break;
            case WorkshopTime::class:
                $formConfig = $time->getWorkshop()->getFormConfig();
                break;
            default:
                throw new \Exception(sprintf('Unsuported class %s', get_class($time)));
                break;
        }

        $form = new Form($formConfig->getConfig());

        return $form;
    }
}
