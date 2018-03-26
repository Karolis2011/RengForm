<?php

namespace App\Repository;

use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Workshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workshop[]    findAll()
 * @method Workshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopRepository extends ServiceEntityRepository
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
     * @param Workshop $workshop
     * @param bool     $flush
     */
    public function save(Workshop $workshop, bool $flush = true): void
    {
        $workshop->setCreated(new \DateTime());
        /** @var WorkshopTime $time */
        foreach ($workshop->getTimes() as $time) {
            $time->setWorkshop($workshop);
        }

        $this->_em->persist($workshop);
        if ($flush) {
            $this->_em->flush($workshop);
        }
    }

    /**
     * @param Workshop $workshop
     * @param bool     $flush
     */
    public function update(Workshop $workshop, bool $flush = true): void
    {
        /** @var WorkshopTime $time */
        foreach ($workshop->getTimes() as $time) {
            $time->setWorkshop($workshop);
        }

        $this->_em->merge($workshop);
        if ($flush) {
            $this->_em->flush($workshop);
        }
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
