<?php

namespace App\Tests\Unit\Service\Form\Validator;

use App\Service\Form\FormField;
use App\Service\Form\Validator\DateField;
use PHPUnit\Framework\TestCase;

/**
 * Class DateFieldTest
 */
class DateFieldTest extends TestCase
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
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
            ]),
            [
                'txt' => '2018-01-01',
            ],
            [],
        ];

        //case #1
        $cases[] = [
            new FormField([
                'type'     => 'date',
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
                'type'     => 'date',
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
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [],
            [],
        ];

        //case #4
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '',
            ],
            [],
        ];

        //case #5
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '2018-01-01',
            ],
            [],
        ];

        //case #6
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '2018-1-01',
            ],
            [
                'Test format is not valid, must be YYYY-MM-DD'
            ],
        ];

        //case #7
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '2018',
            ],
            [
                'Test format is not valid, must be YYYY-MM-DD'
            ],
        ];

        //case #8
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '2018 Match 18th',
            ],
            [
                'Test format is not valid, must be YYYY-MM-DD'
            ],
        ];

        //case #9
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '20180101',
            ],
            [
                'Test format is not valid, must be YYYY-MM-DD'
            ],
        ];

        //case #10
        $cases[] = [
            new FormField([
                'type'     => 'date',
                'name'     => 'txt',
                'label'    => 'Test',
            ]),
            [
                'txt' => '01-01-2018',
            ],
            [
                'Test format is not valid, must be YYYY-MM-DD'
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
        $errors = DateField::validate($field, $formData);
        $this->assertEquals($expected, $errors, '', 0.0, 10, true);
    }
}
