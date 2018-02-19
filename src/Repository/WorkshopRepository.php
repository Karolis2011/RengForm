<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

        $this->_em->persist($workshop);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Workshop $workshop
     * @param bool     $flush
     */
    public function update(Workshop $workshop, bool $flush = true): void
    {
        $this->_em->merge($workshop);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
