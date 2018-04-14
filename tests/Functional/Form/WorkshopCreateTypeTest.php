<?php

namespace App\Tests\Functional\Form;

use App\Entity\Category;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Form\WorkshopCreateType;
use App\Tests\TestCases\TypeDatabaseTestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\PreloadedExtension;

/**
 * Class WorkshopCreateTypeTest
 */
class WorkshopCreateTypeTest extends TypeDatabaseTestCase
{
    public function testSubmitValid()
    {
        $formData = [
            'title'       => 'a',
            'description' => 'b',
            'place'       => 'c',
            'duration'    => '01:00',
            'capacity'    => '10',
            'formConfig'  => 'ebe13752-384c-11e8-9074-080027c702a7',
            'category'    => '89cacd61-38c0-11e8-9074-080027c702a7',
            'times'       => [
                ['startTime' => '2018-01-01 01:01'],
            ],
        ];

        $objectToCompare = new Workshop();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(
            WorkshopCreateType::class,
            $objectToCompare,
            [
                'eventId' => 'ae9f01c4-38bb-11e8-9074-080027c702a7',
            ]
        );

        $object = new Workshop();
        $object->setTitle('a');
        $object->setDescription('b');
        $object->setPlace('c');
        $object->setDuration(new \DateTime('1970-01-01 01:00:00'));
        $object->setCapacity(10);
        $object->setFormConfig(
            $this->getEntityManager()->getRepository(FormConfig::class)
                ->find('ebe13752-384c-11e8-9074-080027c702a7')
        );
        $object->setCategory(
            $this->getEntityManager()->getRepository(Category::class)
                ->find('89cacd61-38c0-11e8-9074-080027c702a7')
        );
        $time = new WorkshopTime();
        $time->setStartTime(new \DateTime('2018-01-01 01:01'));
        $object->getTimes()->add($time);
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @return array
     */
    protected function getClasses(): array
    {
        $classes = [
            FormConfig::class,
            Category::class,
            MultiEvent::class,
        ];

        return $classes;
    }

    /**
     * @return string
     */
    protected function getPathToFixture(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/workshop_create_type_test_data.sql';
    }

    protected function getExtensions()
    {
        // create a type instance with the mocked dependencies
        $type = new EntityType($this->getManagerRegistry());

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$type], []),
        ];
    }
}
