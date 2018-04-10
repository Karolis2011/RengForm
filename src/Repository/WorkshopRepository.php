<?php

namespace App\Repository;

use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Workshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workshop[]    findAll()
 * @method Workshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(Workshop $object, bool $flush = true): void
 * @method remove(Workshop $object, bool $flush = true): void
 */
class WorkshopRepository extends AbstractRepository
{
    /**
     * WorkshopRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    /**
     * @param Workshop $object
     * @param bool     $flush
     */
    public function save($object, bool $flush = true): void
    {
        $object->setCreated(new \DateTime());
        /** @var WorkshopTime $time */
        foreach ($object->getTimes() as $time) {
            $time->setWorkshop($object);
        }

        parent::save($object, $flush);
    }

    /**
     * @param $eventId
     * @return array
     */
    public function getByEventId($eventId): array
    {
        $query = $this->createQueryBuilder('w')
            ->select('w')
            ->leftJoin('w.category', 'c')
            ->leftJoin('c.event', 'e')
            ->where('e.id = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery();

        $workshops = $query->getResult();

        return $workshops;
    }
}
