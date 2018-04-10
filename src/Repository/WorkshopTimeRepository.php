<?php

namespace App\Repository;

use App\Entity\WorkshopTime;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WorkshopTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkshopTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkshopTime[]    findAll()
 * @method WorkshopTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method save(WorkshopTime $object, bool $flush = true): void
 * @method update(WorkshopTime $object, bool $flush = true): void
 * @method remove(WorkshopTime $object, bool $flush = true): void
 */
class WorkshopTimeRepository extends AbstractRepository
{
    /**
     * WorkshopTimeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WorkshopTime::class);
    }
}
