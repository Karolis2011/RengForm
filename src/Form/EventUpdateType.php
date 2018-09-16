<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\FormConfig;
use App\Repository\FormConfigRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventUpdateType
 */
class EventUpdateType extends AbstractType
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
                'duration',
                TimeType::class,
                [
                    'widget'   => 'single_text',
                    'html5'    => false,
                    'required' => true,
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
                    'class'         => FormConfig::class,
                    'choice_label'  => 'title',
                    'placeholder'   => '',
                    'required'      => false,
                    'query_builder' => function (FormConfigRepository $repository) use ($options) {
                        return $repository->createGetByOwnerIdQuery($options['ownerId'], false);
                    },
                ]
            )
            ->add(
                'groupFormConfig',
                EntityType::class,
                [
                    'class'         => FormConfig::class,
                    'choice_label'  => 'title',
                    'placeholder'   => '',
                    'required'      => false,
                    'query_builder' => function (FormConfigRepository $repository) use ($options) {
                        return $repository->createGetByOwnerIdQuery($options['ownerId'], true);
                    },
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ])
            ->setRequired([
                'ownerId',
            ]);
    }
}
