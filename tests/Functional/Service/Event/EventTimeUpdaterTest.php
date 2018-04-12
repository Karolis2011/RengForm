<?php

namespace App\Tests\Functional\Service\Event;

use App\Entity\EventTime;
use App\Entity\WorkshopTime;
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
        EventTime::class,
        WorkshopTime::class,
    ];

    public function testUpdate()
    {
        $updater = $this->getContainer()->get('test.event_time_updater');
        //TODO: test
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
