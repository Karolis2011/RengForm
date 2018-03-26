<?php

namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Registration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registration[]    findAll()
 * @method Registration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationRepository extends ServiceEntityRepository
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
     * @param Registration $registration
     * @param bool         $flush
     */
    public function save(Registration $registration, bool $flush = true): void
    {
        $registration->setCreated(new \DateTime());

        $this->_em->persist($registration);
        if ($flush) {
            $this->_em->flush($registration);
        }
    }

    /**
     * @param Registration $registration
     * @param bool         $flush
     */
    public function update(Registration $registration, bool $flush = true): void
    {
        $this->_em->merge($registration);
        if ($flush) {
            $this->_em->flush($registration);
        }
    }
}
