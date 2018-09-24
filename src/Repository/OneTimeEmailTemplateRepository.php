<?php

namespace App\Repository;

use App\Entity\OneTimeEmailTemplate;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OneTimeEmailTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method OneTimeEmailTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method OneTimeEmailTemplate[]    findAll()
 * @method OneTimeEmailTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(OneTimeEmailTemplate $object, bool $flush = true): void
 * @method remove(OneTimeEmailTemplate $object, bool $flush = true): void
 */
class OneTimeEmailTemplateRepository extends AbstractRepository
{
    /**
     * EmailTemplateRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OneTimeEmailTemplate::class);
    }
}
