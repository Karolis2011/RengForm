<?php

namespace App\Repository;

use App\Entity\Registration;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Registration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registration[]    findAll()
 * @method Registration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(Registration $object, bool $flush = true): void
 * @method remove(Registration $object, bool $flush = true): void
 */
class RegistrationRepository extends AbstractRepository
{
    /**
     * RegistrationRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    /**
     * @param Registration $object
     * @param bool         $flush
     */
    public function save($object, bool $flush = true): void
    {
        $object->setCreated(new \DateTime());

        parent::save($object, $flush);
    }
}
