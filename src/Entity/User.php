<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organisation", inversedBy="users")
     */
    private $organisations;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->organisations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOrganisations()
    {
        return $this->organisations;
    }

    /**
     * @param Organisation $organisation
     */
    public function addOrganisation(Organisation $organisation): void
    {
        if ($this->organisations->contains($organisation)) {
            return;
        }

        $this->organisations[] = $organisation;
        $organisation->addUser($this);
    }

    /**
     * @param Organisation $organisation
     */
    public function removeOrganisation(Organisation $organisation): void
    {
        if ($this->organisations->contains($organisation)) {
            $this->organisations->removeElement($organisation);
            $organisation->removeUser($this);
        }
    }
}
