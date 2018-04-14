<?php

namespace App\Tests\Unit\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class UserTypeTest
 */
class UserTypeTest extends TypeTestCase
{
    public function testSubmitValid()
    {
        $formData = [
            'email'         => 'admin@renform.test',
            'username'      => 'admin',
            'plainPassword' => [
                'first'  => 'aaa',
                'second' => 'aaa',
            ],
        ];

        $objectToCompare = new User();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(UserType::class, $objectToCompare);

        $object = new User();
        $object->setEmail('admin@renform.test');
        $object->setUsername('admin');
        $object->setPlainPassword('aaa');
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
