<?php

namespace App\Tests\Unit\Form;

use App\Form\EventTimeModelCollectionType;
use App\Form\Model\EventTimeModel;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class EventTimeModelCollectionTypeTest
 */
class EventTimeModelCollectionTypeTest extends TypeTestCase
{
    public function testSubmitValid()
    {
        $formData = [
            'times' => [
                [
                    'id'        => 'a',
                    'startTime' => '2017-01-01 01:01',
                ],
            ],
        ];

        $expectedData = [
            'times' => [],
        ];
        $model = new EventTimeModel();
        $model->setId('a');
        $model->setStartTime(new \DateTime('2017-01-01 01:01'));
        $expectedData['times'][] = $model;

        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(EventTimeModelCollectionType::class);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($expectedData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
