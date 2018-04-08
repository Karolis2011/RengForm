<?php

namespace App\Repository;

use App\Entity\FormConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FormConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormConfig[]    findAll()
 * @method FormConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormConfigRepository extends ServiceEntityRepository
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
     * @param FormConfig $formConfig
     * @param bool       $flush
     */
    public function save(FormConfig $formConfig, bool $flush = true): void
    {
        $formConfig->setCreated(new \DateTime());

        $this->_em->persist($formConfig);
        if ($flush) {
            $this->_em->flush($formConfig);
        }
    }

    /**
     * @param FormConfig $formConfig
     * @param bool       $flush
     */
    public function update(FormConfig $formConfig, bool $flush = true): void
    {
        $this->_em->merge($formConfig);
        if ($flush) {
            $this->_em->flush($formConfig);
        }
    }

    /**
     * @param FormConfig $formConfig
     * @param bool       $flush
     */
    public function remove(FormConfig $formConfig, bool $flush = true): void
    {
        $this->_em->remove($formConfig);
        if ($flush) {
            $this->_em->flush($formConfig);
        }
    }
}
