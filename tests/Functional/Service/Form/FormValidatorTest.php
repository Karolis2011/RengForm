<?php

namespace App\Tests\Functional\Service\Form;

use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class FormValidatorTest
 */
class FormValidatorTest extends WebTestCase
{
    /**
     * @return array
     */
    public function getTestValidateData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            [
                [
                    'type'     => 'text',
                    'name'     => 'txt',
                    'label'    => 'Test',
                    'required' => true,
                ],
            ],
            [
                'txt' => 'a',
            ],
            true,
        ];

        //case #1
        $cases[] = [
            [
                [
                    'type'     => 'text',
                    'name'     => 'txt',
                    'label'    => 'Test',
                    'required' => true,
                ],
            ],
            [],
            false,
        ];

        //case #2
        $cases[] = [
            [
                [
                    'type'     => 'text',
                    'name'     => 'txt',
                    'label'    => 'Test',
                    'required' => true,
                ],
                [
                    'type'     => 'date',
                    'name'     => 'dt',
                    'label'    => 'Test',
                    'required' => true,
                ],
                [
                    'type'     => 'checkbox-group',
                    'name'     => 'cbg',
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
                ],
                [
                    'type'     => 'number',
                    'name'     => 'nm',
                    'label'    => 'Test',
                    'required' => true,
                ],
                [
                    'type'     => 'radio-group',
                    'name'     => 'rdg',
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
                ],
                [
                    'type'     => 'select',
                    'name'     => 'sl',
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
                ],
                [
                    'type'     => 'textarea',
                    'name'     => 'txta',
                    'label'    => 'Test',
                    'required' => true,
                ],
            ],
            [
                'txt'  => 'a',
                'dt'   => '2018-01-01',
                'cbg'  => ['a'],
                'nm'   => '1',
                'rdg'  => ['a'],
                'sl'   => 'a',
                'txta' => 'a',
            ],
            true,
        ];

        //case #3
        $cases[] = [
            [
                [
                    'type'     => 'text',
                    'name'     => 'txt',
                    'label'    => 'Test',
                    'required' => true,
                ],
                [
                    'type'     => 'date',
                    'name'     => 'dt',
                    'label'    => 'Test',
                    'required' => true,
                ],
                [
                    'type'     => 'checkbox-group',
                    'name'     => 'cbg',
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
                ],
                [
                    'type'     => 'number',
                    'name'     => 'nm',
                    'label'    => 'Test',
                    'required' => true,
                ],
                [
                    'type'     => 'radio-group',
                    'name'     => 'rdg',
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
                ],
                [
                    'type'     => 'select',
                    'name'     => 'sl',
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
                ],
                [
                    'type'     => 'textarea',
                    'name'     => 'txta',
                    'label'    => 'Test',
                    'required' => true,
                ],
            ],
            [
                'txt'  => 'a',
                'dt'   => '2018-01-01',
                'nm'   => '1',
                'rdg'  => ['a'],
                'sl'   => 'a',
                'txta' => 'a',
            ],
            false,
        ];

        //case #4
        $cases[] = [
            [
                [
                    'type'     => 'texasdt',
                    'name'     => 'txt',
                    'label'    => 'Test',
                    'required' => true,
                ],
            ],
            [
                'txt' => 'a',
            ],
            false,
        ];

        return $cases;
    }

    public function testValidateFail()
    {
        $validator = $this->createClient()->getContainer()->get('test.form_validator');
        $result = $validator->validate(new FormConfig(), ['a']);
        $this->assertEquals(false, $result);
    }

    /**
     * @dataProvider getTestValidateData
     * @param $formConfig
     * @param $formData
     * @param $expected
     * @throws \Exception
     */
    public function testValidate($formConfig, $formData, $expected)
    {
        $validator = $this->createClient()->getContainer()->get('test.form_validator');
        $event = $this->getEvent($formConfig);
        $result = $validator->validate($event, $formData);
        $this->assertEquals($expected, $result);

        $workshop = $this->getWorkshop($formConfig);
        $result = $validator->validate($workshop, $formData);
        $this->assertEquals($expected, $result);
    }

    /**
     * @param array $formConfig
     * @return EventTime
     */
    private function getEvent(array $formConfig): EventTime
    {
        $form = new FormConfig();
        $form->setConfig(json_encode($formConfig));

        $event = new Event();
        $event->setFormConfig($form);

        $eventTime = new EventTime();
        $eventTime->setEvent($event);

        return $eventTime;
    }

    /**
     * @param array $formConfig
     * @return WorkshopTime
     */
    private function getWorkshop(array $formConfig): WorkshopTime
    {
        $form = new FormConfig();
        $form->setConfig(json_encode($formConfig));

        $workshop = new Workshop();
        $workshop->setFormConfig($form);

        $eventTime = new WorkshopTime();
        $eventTime->setWorkshop($workshop);

        return $eventTime;
    }
}
