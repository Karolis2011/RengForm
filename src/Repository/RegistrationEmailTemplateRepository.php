<?php

namespace App\Repository;

use App\Entity\RegistrationEmailTemplate;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RegistrationEmailTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistrationEmailTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistrationEmailTemplate[]    findAll()
 * @method RegistrationEmailTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(RegistrationEmailTemplate $object, bool $flush = true): void
 */
class RegistrationEmailTemplateRepository extends AbstractRepository
{
    /**
     * EmailTemplateRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RegistrationEmailTemplate::class);
    }

    /**
     * @param RegistrationEmailTemplate $object
     * @param bool                      $flush
     */
    public function remove($object, bool $flush = true): void
    {
        $formConfig = $object->getFormConfig();
        $formConfig->setRegistrationEmailTemplate(null);
        $this->_em->persist($formConfig);

        parent::remove($object, $flush);
    }
}
