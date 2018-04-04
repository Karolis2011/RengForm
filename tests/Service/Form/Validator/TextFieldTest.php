<?php

namespace App\Tests\Service\Form\Validator;

use App\Service\Form\FormField;
use App\Service\Form\Validator\TextField;
use PHPUnit\Framework\TestCase;

/**
 * Class TextFieldTest
 */
class TextFieldTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestValidateData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
            ]),
            [
                'txt' => 'a',
            ],
            [],
        ];

        //case #1
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
            ]),
            [
                'txt' => '',
            ],
            [
                'Test is required',
            ],
        ];

        //case #2
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
            ]),
            [],
            [
                'Test is required',
            ],
        ];

        //case #3
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'maxlength' => 5,
            ]),
            [],
            [],
        ];

        //case #4
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'maxlength' => 5,
            ]),
            [
                'txt' => '123'
            ],
            [],
        ];

        //case #5
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'maxlength' => 5,
            ]),
            [
                'txt' => '12345'
            ],
            [],
        ];

        //case #6
        $cases[] = [
            new FormField([
                'type'     => 'text',
                'name'     => 'txt',
                'label'    => 'Test',
                'maxlength' => 5,
            ]),
            [
                'txt' => '123456'
            ],
            [
                'Test can not be longer than 5 characters'
            ],
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestValidateData
     * @param $field
     * @param $formData
     * @param $expected
     */
    public function testValidate($field, $formData, $expected)
    {
        $errors = TextField::validate($field, $formData);
        $this->assertEquals($expected, $errors, '', 0.0, 10, true);
    }
}
