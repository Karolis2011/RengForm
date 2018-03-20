<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\FormConfig;
use App\Entity\Workshop;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WorkshopType
 */
class WorkshopType extends AbstractType
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
                'startTime',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'html5'  => false,
                ]
            )
            ->add(
                'endTime',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'html5'  => false,
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
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class'         => Category::class,
                    'choice_label'  => 'title',
                    'placeholder'   => '',
                    'query_builder' => function (CategoryRepository $repository) use ($options) {
                        return $repository->createGetByEventIdQuery($options['eventId']);
                    },
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
                'data_class' => Workshop::class,
            ])
            ->setRequired([
                'eventId'
            ]);
    }
}
