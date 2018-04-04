<?php

namespace App\Tests\Functional\Service\Form;

use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
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
                ]
            ],
            [
                'txt' => 'a'
            ],
            true
        ];

        //case #1
        $cases[] = [
            [
                [
                    'type'     => 'text',
                    'name'     => 'txt',
                    'label'    => 'Test',
                    'required' => true,
                ]
            ],
            [],
            false
        ];

        //TODO: add full form test case

        return $cases;
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
    }

    /**
     * @param array $formConfig
     * @return EventTime
     */
    private function getEvent(array $formConfig): EventTime
    {
        $form = new FormConfig();
        $form->setConfig($formConfig);

        $event = new Event();
        $event->setFormConfig($form);

        $eventTime = new EventTime();
        $eventTime->setEvent($event);

        return $eventTime;
    }
}
