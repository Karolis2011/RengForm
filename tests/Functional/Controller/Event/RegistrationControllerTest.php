<?php

namespace App\Tests\Functional\Controller\Event;

use App\Entity\Category;
use App\Entity\RegistrationEmailTemplate;
use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Registration;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Tests\TestCases\DatabaseTestCase;

/**
 * Class RegistrationControllerTest
 */
class RegistrationControllerTest extends DatabaseTestCase
{
    /**
     * @return array
     */
    public function getTestRegisterData(): array
    {
        $cases = [];

        //case #0
        $cases[] = [
            null,
            [],
            500,
        ];

        //case #1
        $cases[] = [
            '367c42c6-3863-11e8-9074-080027c702a7',
            [
                'registration' => [
                    'txt' => 'aaa',
                ],
            ],
            200,
        ];

        //case #1
        $cases[] = [
            '9b65be4a-38c0-11e8-9074-080027c702a7',
            [
                'registration' => [
                    'txt' => 'aaa',
                ],
            ],
            200,
        ];

        return $cases;
    }

    /**
     * @dataProvider getTestRegisterData
     * @param null|string $timeId
     * @param array       $formData
     * @param int         $expected
     */
    public function testRegister(?string $timeId, array $formData, int $expected)
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/en/register/' . $timeId . '/simple',
            $formData,
            [],
            []
        );
        $response = $client->getResponse();
        $this->assertEquals($expected, $response->getStatusCode());

        if ($formData !== []) {
            $registrations = $this->getEntityManager()->getRepository(Registration::class)->findAll();
            $this->assertCount(1, $registrations);
            /** @var EventTime[]|WorkshopTime[] $timeIdRegistered */
            $timeIdRegistered = array_values(array_filter([
                $registrations[0]->getEventTime(),
                $registrations[0]->getWorkshopTime(),
            ]));
            $this->assertCount(1, $timeIdRegistered);
            $this->assertEquals($timeId, $timeIdRegistered[0]->getId());
            $this->assertEquals($formData['registration'], $registrations[0]->getData());
        }
    }

    /**
     * @return array
     */
    protected function getClasses(): array
    {
        $classes = [
            WorkshopTime::class,
            EventTime::class,
            Registration::class,
            FormConfig::class,
            Event::class,
            Workshop::class,
            Category::class,
            MultiEvent::class,
            RegistrationEmailTemplate::class,
        ];

        return $classes;
    }

    /**
     * @return string
     */
    protected function getPathToFixture(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/registration_controller_test_data.sql';
    }
}
