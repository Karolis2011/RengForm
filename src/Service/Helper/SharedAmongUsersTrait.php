<?php

namespace App\Service\Helper;

use App\Entity\User;
use App\Repository\AbstractRepository;

/**
 * Trait SharedAmongUsersTrait
 */
trait SharedAmongUsersTrait
{
    /**
     * @param AbstractRepository $repository
     * @return array
     */
    private function findAllEntities(AbstractRepository $repository)
    {
        if ($this->getParameter('shared_events')) {
            return $repository->findAll();
        }

        $user = $this->getUser();

        return $repository->findBy(['owner' => $user]);
    }

    /**
     * @param AbstractRepository $repository
     * @param string|int         $objectId
     * @return null|object
     */
    private function findEntity(AbstractRepository $repository, $objectId)
    {
        if ($this->getParameter('shared_events')) {
            return $repository->find($objectId);
        }

        $user = $this->getUser();

        return $repository->findOneBy(['id' => $objectId, 'owner' => $user]);
    }

    /**
     * @param User $user
     * @return bool
     */
    private function isOwner(User $user): bool
    {
        if ($this->getParameter('shared_events')) {
            return true;
        }

        /** @var User $loggedInUser */
        $loggedInUser = $this->getUser();

        return $loggedInUser->getId() == $user->getId();
    }

    /**
     * @param string $name
     * @return mixed
     */
    abstract protected function getParameter(string $name);

    /**
     * @return mixed
     */
    abstract protected function getUser();
}
