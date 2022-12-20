<?php

namespace Adopet\Model\Entity;

class Pets
{
    private ?int $id;
    private string $name;
    private string $age;
    private string $size;
    private string $feature;
    private string $city;
    private string $tel;
    private ?string $photo;

    public function __construct(
        int $id = 0,
        string $name="",
        string $age="",
        string $size="",
        string $feature="",
        string $city="",
        string $tel="",
        string $photo=""
    ) {
        $this->setId($id);
        $this->setName($name);
        $this->setAge($age);
        $this->setSize($size);
        $this->setFeature($feature);
        $this->setCity($city);
        $this->setTel($tel);
        $this->setPhoto($photo);
    }

    public function getId(): int
    {
       return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAge(): string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;
        return $this;
    }
    

    public function getFeature(): string
    {
        return $this->feature;
    }

    public function setFeature(string $feature): self
    {
        $this->feature = $feature;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getTel(): string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;
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
        if($photo === null){
            $this->photo = '';
            return $this;
        }
        $this->photo = $photo;

        return $this;
    }
}
