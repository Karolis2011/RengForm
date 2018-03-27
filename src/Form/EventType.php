<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\FormConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventType
 */
class EventType extends AbstractType
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
                'place',
                TextType::class
            )
            ->add(
                'times',
                CollectionType::class,
                [
                    'entry_type'   => WorkshopTimeType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'required'     => true,
                ]
            )
            ->add(
                'duration',
                TimeType::class,
                [
                    'widget'   => 'single_text',
                    'html5'    => false,
                    'required' => true,
                    'data'     => new \DateTime('01:00'),
                ]
            )
            ->add(
                'capacity',
                NumberType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'formConfig',
                EntityType::class,
                [
                    'class'        => FormConfig::class,
                    'choice_label' => 'title',
                    'placeholder'  => '',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Event::class,
            ])
            ->setRequired([
                'eventId',
            ]);
    }
}
