<?php

namespace Adopet\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private    int $id;

    /** @ORM\Column(type="string",length=255) */
    private string $name;
    /** @ORM\Column(type="string", unique=true, length=255) */
    private string $email;
    /** @ORM\Column(type="string", length=255) */
    private string $password;
    /** @ORM\Column(type="boolean") */
    private bool   $mailValidation;

    /**
     * One Customer has One Cart.
     * @ORM\OneToOne(targetEntity=Perfil::class, mappedBy="user", cascade={"remove"})
     */
    private Perfil $perfil;

    /**
     * One product has many features. This is the inverse side.

     * @ORM\OneToMany(targetEntity=Pets::class, mappedBy="user", cascade={"remove"})
     */
    private ?Collection $pets;

    /** @ORM\Column(type="datetime") */
    private \DateTime $created_at;
    /** @ORM\Column(type="datetime") */
    private \DateTime $updated_at;

    public function __construct(string $name=null, string $email=null, string $password=null)
    {
        $this->pets = new ArrayCollection();
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $password = md5($password);
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of mailValidation
     */ 
    public function getMailValidation()
    {
        return $this->mailValidation;
    }

    /**
     * Set the value of mailValidation
     *
     * @return  self
     */ 
    public function setMailValidation($mailValidation)
    {
        $this->mailValidation = $mailValidation;

        return $this;
    }

    /**
     * Get the value of perfil
     */ 
    public function getPerfil(): Perfil
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */ 
    public function setPerfil(Perfil $perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * @return Collection<Pets>
     */

    public function getPets():Collection
    {
        return $this->pets;
    }

    public function addPets(Pets $pets)
    {
        $this->pets->add($pets);
        $pets->setUser($this);
    }
}
