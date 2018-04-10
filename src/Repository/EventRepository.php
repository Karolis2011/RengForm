<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventTime;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(Event $object, bool $flush = true): void
 * @method remove(Event $object, bool $flush = true): void
 */
class EventRepository extends AbstractRepository
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
     * @param Event $object
     * @param bool  $flush
     */
    public function save($object, bool $flush = true): void
    {
        $object->setCreated(new \DateTime());
        /** @var EventTime $time */
        foreach ($object->getTimes() as $time) {
            $time->setEvent($object);
        }

        parent::save($object, $flush);
    }
}
