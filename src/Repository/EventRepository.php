<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
}
