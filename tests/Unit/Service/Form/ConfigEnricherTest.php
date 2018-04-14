<?php

namespace App\Tests\Unit\Service\Form;

use App\Entity\FormConfig;
use App\Service\Form\ConfigEnricher;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigEnricherTest
 */
class ConfigEnricherTest extends TestCase
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

        return $cases;
    }

    /**
     * @dataProvider getTestEnrichData
     * @param array $config
     * @param array $expected
     */
    public function testEnrich(array $config, array $expected)
    {
        $enricher = new ConfigEnricher();
        $formConfig = new FormConfig();
        $formConfig->setConfig(json_encode($config));
        $enricher->enrich($formConfig);

        $this->assertEquals($expected, $formConfig->getConfigParsed());
    }
}
