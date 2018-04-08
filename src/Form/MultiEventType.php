<?php

namespace App\Form;

use App\Entity\MultiEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MultiEventType
 */
class MultiEventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'date',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'html5'  => false,
                ]
            )
            ->add(
                'endDate',
                DateTimeType::class,
                [
                    'widget'   => 'single_text',
                    'html5'    => false,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MultiEvent::class,
        ]);
    }
}
