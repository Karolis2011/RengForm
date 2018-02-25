<?php

namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
            $this->_em->flush();
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
            $this->_em->flush();
        }
    }
}
