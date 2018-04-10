<?php

namespace App\Repository;

use App\Entity\MultiEvent;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MultiEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultiEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultiEvent[]    findAll()
 * @method MultiEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(MultiEvent $object, bool $flush = true): void
 * @method remove(MultiEvent $object, bool $flush = true): void
 */
class MultiEventRepository extends AbstractRepository
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
     * @param MultiEvent $object
     * @param bool       $flush
     */
    public function save($object, bool $flush = true): void
    {
        $object->setCreated(new \DateTime());

        parent::save($object, $flush);
    }
}
