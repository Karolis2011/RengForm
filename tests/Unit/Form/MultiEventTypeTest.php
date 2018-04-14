<?php

namespace App\Tests\Unit\Form;

use App\Entity\MultiEvent;
use App\Form\MultiEventType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class MultiEventTypeTest
 */
class MultiEventTypeTest extends TypeTestCase
{
    public function testSubmitValid()
    {
        $formData = [
            'title'       => 'a',
            'description' => 'b',
            'date'        => '2017-01-01 10:00',
            'endDate'     => '2017-01-01 18:00',
        ];

        $objectToCompare = new MultiEvent();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(MultiEventType::class, $objectToCompare);

        $object = new MultiEvent();
        $object->setTitle('a');
        $object->setDescription('b');
        $object->setDate(new \DateTime('2017-01-01 10:00:00'));
        $object->setEndDate(new \DateTime('2017-01-01 18:00:00'));
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
}
