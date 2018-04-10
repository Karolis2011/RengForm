<?php

namespace App\Repository;

use App\Entity\EventTime;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventTime[]    findAll()
 * @method EventTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method save(EventTime $object, bool $flush = true): void
 * @method update(EventTime $object, bool $flush = true): void
 * @method remove(EventTime $object, bool $flush = true): void
 */
class EventTimeRepository extends AbstractRepository
{
    /**
     * EventTimeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventTime::class);
    }
}
