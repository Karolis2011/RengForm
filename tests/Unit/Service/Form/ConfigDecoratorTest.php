<?php

namespace App\Tests\Unit\Service\Form;

use App\Entity\FormConfig;
use App\Service\Form\ConfigDecorator;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigDecoratorTest
 */
class ConfigDecoratorTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestEnrichData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            [
                [
                    'type' => 'paragraph',
                    'name' => 'prg',
                ],
                [
                    'type'  => 'text',
                    'label' => 'Text Field One',
                ],
                [
                    'type'  => 'text',
                    'label' => 'Text Field One',
                ],
                [
                    'type'  => 'text',
                    'label' => 'Text Field Two',
                ],
            ],
            [
                [
                    'type' => 'paragraph',
                    'name' => 'prg',
                ],
                [
                    'type'  => 'text',
                    'label' => 'Text Field One',
                    'name'  => 'text-field-one',
                ],
                [
                    'type'  => 'text',
                    'label' => 'Text Field One',
                    'name'  => 'text-field-one-1',
                ],
                [
                    'type'  => 'text',
                    'label' => 'Text Field Two',
                    'name'  => 'text-field-two',
                ],
            ],
        ];

        //case #1 - lithuanian letters
        $cases[] = [
            [
                [
                    'type'  => 'text',
                    'label' => 'ąčęėįšųūž',
                ],
                [
                    'type'  => 'text',
                    'label' => 'ĄČĘĖĮŠŲŪŽ',
                ],
            ],
            [
                [
                    'type'  => 'text',
                    'label' => 'ąčęėįšųūž',
                    'name'  => 'aceeisuuz',
                ],
                [
                    'type'  => 'text',
                    'label' => 'ĄČĘĖĮŠŲŪŽ',
                    'name'  => 'aceeisuuz-1',
                ],
            ],
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestEnrichData
     * @param array $config
     * @param array $expected
     */
    public function testEnrich(array $config, array $expected)
    {
        $decorator = new ConfigDecorator();
        $formConfig = new FormConfig();
        $formConfig->setConfig(json_encode($config));
        $decorator->decorate($formConfig);

        $this->assertEquals($expected, $formConfig->getConfigParsed());
    }
}
