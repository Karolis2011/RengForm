<?php

namespace App\Tests\TestCases;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait DatabaseTestCase
 */
abstract class DatabaseTestCase extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $realContainer;

    /**
     * @var Client
     */
    protected $client;

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
    protected function importFixtures()
    {
        $pathToFixture = $this->getPathToFixture();
        $fixture = file_get_contents($pathToFixture);
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
    protected function createSchema()
    {
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);
        $schemaClasses = $this->getClasses();
        $classes = [];

        if (null !== $schemaClasses) {
            foreach ($schemaClasses as $class) {
                $classes[] = $entityManager->getClassMetadata($class);
            }
        } else {
            $classes = $entityManager->getMetadataFactory()->getAllMetadata();
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
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return ManagerRegistry
     */
    protected function getManagerRegistry()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        if (is_null($this->realContainer)) {
            $this->realContainer = $this->getClient()->getContainer();
        }

        return $this->realContainer;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        if (is_null($this->client)) {
            $this->client = static::createClient(['debug' => false]);
        }

        return $this->client;
    }

    /**
     * @return array
     */
    abstract protected function getClasses(): array;

    /**
     * @return string
     */
    abstract protected function getPathToFixture(): string;
}
