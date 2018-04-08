<?php

namespace App\Repository;

use App\Entity\MultiEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MultiEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultiEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultiEvent[]    findAll()
 * @method MultiEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultiEventRepository extends ServiceEntityRepository
{
    /**
     * EventRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MultiEvent::class);
    }

    /**
     * @param MultiEvent $event
     * @param bool       $flush
     */
    public function save(MultiEvent $event, bool $flush = true): void
    {
        $event->setCreated(new \DateTime());

        $this->_em->persist($event);
        if ($flush) {
            $this->_em->flush($event);
        }
    }

    /**
     * @param MultiEvent $event
     * @param bool       $flush
     */
    public function update(MultiEvent $event, bool $flush = true): void
    {
        $this->_em->merge($event);
        if ($flush) {
            $this->_em->flush($event);
        }
    }

    /**
     * @param MultiEvent $event
     * @param bool       $flush
     */
    public function remove(MultiEvent $event, bool $flush = true): void
    {
        $this->_em->remove($event);
        if ($flush) {
            $this->_em->flush($event);
        }
    }
}
