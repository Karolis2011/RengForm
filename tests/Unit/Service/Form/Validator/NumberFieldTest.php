<?php

namespace App\Tests\Unit\Service\Form\Validator;

use App\Service\Form\FormField;
use App\Service\Form\Validator\NumberField;
use PHPUnit\Framework\TestCase;

/**
 * Class NumberFieldTest
 */
class NumberFieldTest extends TestCase
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
                'type'     => 'number',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
            ]),
            [
                'txt' => '1',
            ],
            [],
        ];

        //case #1
        $cases[] = [
            new FormField([
                'type'     => 'number',
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
                'type'     => 'number',
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
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
            ]),
            [],
            [],
        ];

        //case #4
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
                'min'   => '10',
                'max'   => '20',
            ]),
            [],
            [],
        ];

        //case #5
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
            ]),
            [
                'txt' => '',
            ],
            [],
        ];

        //case #6
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
                'min'   => '10',
                'max'   => '20',
            ]),
            [
                'txt' => '',
            ],
            [],
        ];

        //case #7
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
                'min'   => '10',
                'max'   => '20',
            ]),
            [
                'txt' => 10
            ],
            [],
        ];

        //case #8
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
                'min'   => '10',
                'max'   => '20',
            ]),
            [
                'txt' => 20
            ],
            [],
        ];

        //case #9
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
                'min'   => '10',
                'max'   => '20',
            ]),
            [
                'txt' => 9
            ],
            [
                'Test can not be lower than 10'
            ],
        ];

        //case #10
        $cases[] = [
            new FormField([
                'type'  => 'number',
                'name'  => 'txt',
                'label' => 'Test',
                'min'   => '10',
                'max'   => '20',
            ]),
            [
                'txt' => 21
            ],
            [
                'Test can not be higher than 20'
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
        $errors = NumberField::validate($field, $formData);
        $this->assertEquals($expected, $errors, '', 0.0, 10, true);
    }
}
