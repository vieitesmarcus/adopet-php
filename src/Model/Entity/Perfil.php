<?php

namespace Adopet\Model\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="perfil")
 */
class Perfil
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /** @ORM\Column(type="string",length=255) */
    private string $photo;
    /** @ORM\Column(type="string",length=255) */
    private string $name;
    /** @ORM\Column(type="string",length=255) */
    private string $phone;
    /** @ORM\Column(type="string",length=255) */
    private string $city;
    /** @ORM\Column(type="string",length=255) */
    private string $about;

    /**
     * One Cart has One Customer.
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="perfil" )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",columnDefinition="INT NOT NULL")
     */
    private User $user;

    public function __construct(
        string $photo = "",
        string $name = "",
        string $phone = "",
        string $city = "",
        string $about = ""
    ) {
        $this->setPhoto($photo);
        $this->setName($name);
        $this->setPhone($phone);
        $this->setCity($city);
        $this->setAbout($about);
    }


    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of about
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set the value of about
     *
     * @return  self
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
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
     * Get the value of photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }


    /**
     * Get the value of idUser
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
