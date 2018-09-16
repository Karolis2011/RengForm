<?php

namespace App\Repository;

use App\Entity\EmailTemplate;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmailTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailTemplate[]    findAll()
 * @method EmailTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method update(EmailTemplate $object, bool $flush = true): void
 */
class EmailTemplateRepository extends AbstractRepository
{
    /**
     * EmailTemplateRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmailTemplate::class);
    }

    /**
     * @param EmailTemplate $object
     * @param bool          $flush
     */
    public function remove($object, bool $flush = true): void
    {
        $formConfig = $object->getFormConfig();
        $formConfig->setEmailTemplate(null);
        $this->_em->persist($formConfig);

        parent::remove($object, $flush);
    }
}
