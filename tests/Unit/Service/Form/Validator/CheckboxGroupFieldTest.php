<?php

namespace App\Tests\Unit\Service\Form\Validator;

use App\Service\Form\FormField;
use App\Service\Form\Validator\CheckboxGroupField;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckboxGroupFieldTest
 */
class CheckboxGroupFieldTest extends TestCase
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
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [
                'txt' => ['a'],
            ],
            [],
        ];

        //case #1
        $cases[] = [
            new FormField([
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [
                'txt' => [],
            ],
            [
                'Test is required',
            ],
        ];

        //case #2
        $cases[] = [
            new FormField([
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'required' => true,
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [],
            [
                'Test is required',
            ],
        ];

        //case #3
        $cases[] = [
            new FormField([
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [],
            [],
        ];

        //case #4
        $cases[] = [
            new FormField([
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [
                'txt' => []
            ],
            [],
        ];

        //case #5
        $cases[] = [
            new FormField([
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [
                'txt' => ['a', 'b']
            ],
            [],
        ];

        //case #6
        $cases[] = [
            new FormField([
                'type'     => 'checkbox-group',
                'name'     => 'txt',
                'label'    => 'Test',
                'values'   => [
                    [
                        'label' => 'A',
                        'value' => 'a',
                    ],
                    [
                        'label' => 'B',
                        'value' => 'b',
                    ],
                ],
            ]),
            [
                'txt' => ['a', 'b', 'c']
            ],
            [
                "'c' is not valid selection for Test"
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
        $errors = CheckboxGroupField::validate($field, $formData);
        $this->assertEquals($expected, $errors, '', 0.0, 10, true);
    }
}
