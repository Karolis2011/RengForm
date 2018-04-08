<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * EventRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @param Event $event
     * @param bool  $flush
     */
    public function save(Event $event, bool $flush = true): void
    {
        $event->setCreated(new \DateTime());
        /** @var EventTime $time */
        foreach ($event->getTimes() as $time) {
            $time->setEvent($event);
        }

        $this->_em->persist($event);
        if ($flush) {
            $this->_em->flush($event);
        }
    }

    /**
     * @param Event $event
     * @param bool  $flush
     */
    public function update(Event $event, bool $flush = true): void
    {
        $this->_em->merge($event);
        if ($flush) {
            $this->_em->flush($event);
        }
    }

    /**
     * @param Event $event
     * @param bool  $flush
     */
    public function remove(Event $event, bool $flush = true): void
    {
        $this->_em->remove($event);
        if ($flush) {
            $this->_em->flush($event);
        }
    }
}
