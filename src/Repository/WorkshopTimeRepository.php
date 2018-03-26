<?php

namespace App\Repository;

use App\Entity\WorkshopTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WorkshopTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkshopTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkshopTime[]    findAll()
 * @method WorkshopTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopTimeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WorkshopTime::class);
    }

    /**
     * @param WorkshopTime $time
     * @param bool         $flush
     */
    public function save(WorkshopTime $time, bool $flush = true): void
    {
        $this->_em->persist($time);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param WorkshopTime $time
     * @param bool         $flush
     */
    public function update(WorkshopTime $time, bool $flush = true): void
    {
        $this->_em->merge($time);
        if ($flush) {
            $this->_em->flush($time);
        }
    }

    /**
     * @param WorkshopTime $time
     * @param bool         $flush
     */
    public function remove(WorkshopTime $time, bool $flush = true): void
    {
        $this->_em->remove($time);
        if ($flush) {
            $this->_em->flush($time);
        }
    }
}
