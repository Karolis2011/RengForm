<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WorkshopCreateType
 */
class WorkshopCreateType extends WorkshopUpdateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'times',
            CollectionType::class,
            [
                'entry_type'   => WorkshopTimeType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => true,
            ]
        );
    }
}
