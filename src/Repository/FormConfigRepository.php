<?php

namespace App\Repository;

use App\Entity\FormConfig;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FormConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormConfig[]    findAll()
 * @method FormConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(FormConfig $object, bool $flush = true): void
 * @method remove(FormConfig $object, bool $flush = true): void
 */
class FormConfigRepository extends AbstractRepository
{
    /**
     * FormConfigRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FormConfig::class);
    }

    /**
     * @param FormConfig $object
     * @param bool       $flush
     */
    public function save($object, bool $flush = true): void
    {
        $object->setCreated(new \DateTime());

        parent::save($object, $flush);
    }
}
