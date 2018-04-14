<?php

namespace App\Tests\Unit\Form;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CategoryTypeTest
 */
class CategoryTypeTest extends TypeTestCase
{
    public function testSubmitValid()
    {
        $formData = [
            'title'       => 'a',
            'description' => 'b',
        ];

        $objectToCompare = new Category();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(CategoryType::class, $objectToCompare);

        $object = new Category();
        $object->setTitle('a');
        $object->setDescription('b');
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
