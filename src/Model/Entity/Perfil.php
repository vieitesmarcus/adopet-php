<?php

namespace Adopet\Model\Entity;

class Perfil
{
    private int $id;
    private string $photo;
    private string $name;
    private string $phone;
    private string $city;
    private string $about;
    private int $idUser;

    public function __construct(
        int $idUser,
        int    $id    = 0,
        string $photo = "",
        string $name  = "",
        string $phone = "",
        string $city  = "",
        string $about = ""
    ) {
        $this->setIdUser($idUser);
        $this->setId($id);
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
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }
}
