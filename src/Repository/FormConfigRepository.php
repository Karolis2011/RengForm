<?php

namespace App\Repository;

use App\Entity\FormConfig;
use Doctrine\ORM\QueryBuilder;
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

    /**
     * @param      $ownerId
     * @param bool $sharedEvents
     * @param bool $group
     * @return QueryBuilder
     */
    public function createGetByOwnerIdQuery($ownerId, bool $sharedEvents, bool $group): QueryBuilder
    {
        $builder = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.type = :formType');

        if (!$sharedEvents) {
            $builder->leftJoin('c.owner', 'o')
                ->andWhere('o.id = :ownerId')
                ->setParameter('ownerId', $ownerId);
        }

        if ($group) {
            $builder->setParameter('formType', FormConfig::GROUP);
        } else {
            $builder->setParameter('formType', FormConfig::SIMPLE);
        }

        return $builder;
    }
}
