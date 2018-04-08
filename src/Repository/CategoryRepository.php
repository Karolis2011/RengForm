<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
            $this->_em->flush($category);
        }
        $category->setOrderNo($this->getNextOrderNo($category));
        $this->_em->flush($category);
    }

    /**
     * @param Category $category
     * @param bool     $flush
     */
    public function update(Category $category, bool $flush = true): void
    {
        $this->_em->merge($category);
        if ($flush) {
            $this->_em->flush($category);
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

    /**
     * @param Category $category
     * @return int|mixed
     */
    private function getNextOrderNo(Category $category)
    {
        try {
            $query = $this->createQueryBuilder('c')
                ->select('COUNT(c)')
                ->where('c.event = :event')
                ->andWhere('c.orderNo IS NULL OR c.orderNo = 0')
                ->setParameter('event', $category->getEvent())
                ->getQuery();

            $categoriesCount = $query->getSingleScalarResult() + 1;
        } catch (NonUniqueResultException $e) {
            $categoriesCount = 0;
        }

        return $categoriesCount;
    }

    /**
     * @param Category $category
     * @param bool     $flush
     */
    public function remove(Category $category, bool $flush = true): void
    {
        $this->_em->remove($category);
        if ($flush) {
            $this->_em->flush($category);
        }
    }
}
