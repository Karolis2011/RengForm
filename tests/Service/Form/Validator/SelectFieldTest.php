<?php

namespace App\Tests\Service\Form\Validator;

use App\Service\Form\FormField;
use App\Service\Form\Validator\SelectField;
use PHPUnit\Framework\TestCase;

/**
 * Class SelectFieldTest
 */
class SelectFieldTest extends TestCase
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
                'type'     => 'select',
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
                'txt' => 'a',
            ],
            [],
        ];

        //case #1
        $cases[] = [
            new FormField([
                'type'     => 'select',
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
                'txt' => '',
            ],
            [
                'Test is required',
            ],
        ];

        //case #2
        $cases[] = [
            new FormField([
                'type'     => 'select',
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

        //case #3
        $cases[] = [
            new FormField([
                'type'     => 'select',
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

        //case #4
        $cases[] = [
            new FormField([
                'type'   => 'select',
                'name'   => 'txt',
                'label'  => 'Test',
                'values' => [
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
                'txt' => 'a',
            ],
            [],
        ];

        //case #5
        $cases[] = [
            new FormField([
                'type'   => 'select',
                'name'   => 'txt',
                'label'  => 'Test',
                'values' => [
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

        //case #6
        $cases[] = [
            new FormField([
                'type'   => 'select',
                'name'   => 'txt',
                'label'  => 'Test',
                'values' => [
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
                'txt' => '',
            ],
            [],
        ];

        //case #7
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => ['a', 'b'],
            ],
            [],
        ];

        //case #8
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => 'a',
            ],
            [],
        ];

        //case #9
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => '',
            ],
            [],
        ];

        //case #10
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => [''],
            ],
            [],
        ];

        //case #11
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => [''],
            ],
            [
                'Test is required'
            ],
        ];

        //case #12
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
            [],
        ];

        //case #13
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => ['a', 'c'],
            ],
            [
                "'c' is not valid selection for Test",
            ],
        ];

        //case #14
        $cases[] = [
            new FormField([
                'type'     => 'select',
                'name'     => 'txt',
                'label'    => 'Test',
                'multiple' => true,
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
                'txt' => 'c',
            ],
            [
                "'c' is not valid selection for Test",
            ],
        ];

        //case #15
        $cases[] = [
            new FormField([
                'type'   => 'select',
                'name'   => 'txt',
                'label'  => 'Test',
                'values' => [
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
                'txt' => 'c',
            ],
            [
                "'c' is not valid selection for Test",
            ],
        ];

        //case #16
        $cases[] = [
            new FormField([
                'type'   => 'select',
                'name'   => 'txt',
                'label'  => 'Test',
                'values' => [
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
                'txt' => ['a', 'b'],
            ],
            [
                'Only one value can be chosen for Test',
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
        $errors = SelectField::validate($field, $formData);
        $this->assertEquals($expected, $errors, '', 0.0, 10, true);
    }
}
