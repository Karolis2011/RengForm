<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param Category $category
     * @param bool     $flush
     */
    public function save(Category $category, bool $flush = true): void
    {
        $category->setCreated(new \DateTime());

        $this->_em->persist($category);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Category $category
     * @param bool     $flush
     */
    public function update(Category $category, bool $flush = true): void
    {
        $this->_em->merge($category);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $eventId
     * @return QueryBuilder
     */
    public function createGetByEventIdQuery($eventId): QueryBuilder
    {
        $builder = $this->createQueryBuilder('c')
            ->select('c')
            ->leftJoin('c.event', 'e')
            ->where('e.id = :eventId')
            ->setParameter('eventId', $eventId);

        return $builder;
    }
}
