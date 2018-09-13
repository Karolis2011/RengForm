<?php

namespace App\Tests\Unit\Form;

use App\Entity\FormConfig;
use App\Form\FormConfigType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class FormConfigTypeTest
 */
class FormConfigTypeTest extends TypeTestCase
{
    public function testSubmitValid()
    {
        $formData = [
            'title'       => 'a',
            'description' => 'b',
            'config'      => 'c',
            'type'        => 'simple',
        ];

        $objectToCompare = new FormConfig();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(FormConfigType::class, $objectToCompare);

        $object = new FormConfig();
        $object->setTitle('a');
        $object->setDescription('b');
        $object->setConfig('c');
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
