<?php

namespace App\Tests\Functional\Service\Event;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\FormConfig;
use App\Entity\MultiEvent;
use App\Entity\Registration;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use App\Form\Model\EventTimeModel;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventTimeUpdaterTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $classes = [
        MultiEvent::class,
        Event::class,
        EventTime::class,
        Workshop::class,
        WorkshopTime::class,
        FormConfig::class,
        Registration::class,
        Category::class,
    ];

    public function testUpdateFail()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');
        try {
            $updater->update([], new FormConfig());
            $this->assertSame(0, 1, "Should have failed");
        } catch (\Exception $exception) {
            $this->assertSame(1, 1);
        }
    }

    /**
     * @throws \Exception
     */
    public function testUpdateEventTimes()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');

        $event = $this->getEntityManager()->getRepository(Event::class)->find('09606e22-3851-11e8-9074-080027c702a7');
        $newTimes = [];
        $newTimes[0] = new EventTimeModel();
        $newTimes[0]->setId('367c42c6-3863-11e8-9074-080027c702a7');
        $newTimes[0]->setStartTime(new \DateTime('2018-01-01 10:00'));
        $newTimes[1] = new EventTimeModel();
        $newTimes[1]->setStartTime(new \DateTime('2017-01-01 10:00'));
        $updater->update($newTimes, $event);

        $this->getEntityManager()->clear();
        $event = $this->getEntityManager()->getRepository(Event::class)->find('09606e22-3851-11e8-9074-080027c702a7');
        $this->assertCount(2, $event->getTimes());
        foreach ($event->getTimes() as $time) {
            if ($time->getId() == '367c42c6-3863-11e8-9074-080027c702a7') {
                $this->assertSame('2018-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            } else {
                $this->assertSame('2017-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function testUpdateWorkshopTimes()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');

        $event = $this->getEntityManager()->getRepository(Workshop::class)
            ->find('9b65a2ab-38c0-11e8-9074-080027c702a7');
        $newTimes = [];
        $newTimes[0] = new EventTimeModel();
        $newTimes[0]->setId('9b65be4a-38c0-11e8-9074-080027c702a7');
        $newTimes[0]->setStartTime(new \DateTime('2018-01-01 10:00'));
        $newTimes[1] = new EventTimeModel();
        $newTimes[1]->setStartTime(new \DateTime('2017-01-01 10:00'));
        $updater->update($newTimes, $event);

        $this->getEntityManager()->clear();
        $event = $this->getEntityManager()->getRepository(Workshop::class)
            ->find('9b65a2ab-38c0-11e8-9074-080027c702a7');
        $this->assertCount(2, $event->getTimes());
        foreach ($event->getTimes() as $time) {
            if ($time->getId() == '9b65be4a-38c0-11e8-9074-080027c702a7') {
                $this->assertSame('2018-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            } else {
                $this->assertSame('2017-01-01 10:00', $time->getStartTime()->format('Y-m-d H:i'));
            }
        }
    }

    /**
     * @inheritdoc
     * @throws \Doctrine\ORM\Tools\ToolsException
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp()
    {
        $this->createSchema();
        $this->importFixtures();
    }

    /**
     * Import fixtures to database
     * @throws \Doctrine\DBAL\DBALException
     */
    private function importFixtures()
    {
        $fixture = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures/event_time_updater_test_data.sql');
        $fixtures = preg_split('/\n\n/', $fixture);
        /** @var Connection $connection */
        $connection = $this->getConnection();
        foreach ($fixtures as $fixture) {
            if (!empty($fixture)) {
                $connection->executeQuery($fixture);
            }
        }
    }

    /**
     * Create database schema
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    private function createSchema()
    {
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);
        $classes = [];

        foreach ($this->classes as $class) {
            $classes[] = $entityManager->getClassMetadata($class);
        }

        foreach ($classes as &$class) {
            if (isset($class->table['indexes'])) {
                unset($class->table['indexes']);
            }
        }

        $schemaTool->createSchema($classes);
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer()
    {
        if (is_null($this->container)) {
            $this->container = $this->getClient()->getContainer();
        }

        return $this->container;
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        if (is_null($this->client)) {
            $this->client = static::createClient(['debug' => false]);
        }

        return $this->client;
    }
}
