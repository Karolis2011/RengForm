<?php

namespace App\Repository;

use App\Entity\EventTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventTime[]    findAll()
 * @method EventTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventTimeRepository extends ServiceEntityRepository
{
    /**
     * EventTimeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventTime::class);
    }

    /**
     * @param EventTime $time
     * @param bool      $flush
     */
    public function save(EventTime $time, bool $flush = true): void
    {
        $this->_em->persist($time);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param EventTime $time
     * @param bool      $flush
     */
    public function update(EventTime $time, bool $flush = true): void
    {
        $this->_em->merge($time);
        if ($flush) {
            $this->_em->flush($time);
        }
    }

    /**
     * @param EventTime $time
     * @param bool      $flush
     */
    public function remove(EventTime $time, bool $flush = true): void
    {
        $this->_em->remove($time);
        if ($flush) {
            $this->_em->flush($time);
        }
    }
}
