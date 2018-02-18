<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
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
}
