<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @param object $object
     * @param bool   $flush
     */
    public function save($object, bool $flush = true): void
    {
        $this->_em->persist($object);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param object $object
     * @param bool   $flush
     */
    public function update($object, bool $flush = true): void
    {
        $this->_em->merge($object);
        if ($flush) {
            $this->_em->flush($object);
        }
    }

    /**
     * @param object $object
     * @param bool   $flush
     */
    public function remove($object, bool $flush = true): void
    {
        $this->_em->remove($object);
        if ($flush) {
            $this->_em->flush($object);
        }
    }
}
