<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EventTimeModelCollectionType
 */
class EventTimeModelCollectionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'times',
            CollectionType::class,
            [
                'entry_type'   => EventTimeModelType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => true,
            ]
        );
    }
}
