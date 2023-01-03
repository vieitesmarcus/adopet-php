<?php

namespace Adopet\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pets")
 */
class Pets
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id;

    /** @ORM\Column(type="string",length=50) */
    private string $name;
    /** @ORM\Column(type="integer") */
    private int $age;
    /** @ORM\Column(type="string",length=255) */
    private string $size;
    /** @ORM\Column(type="string",length=255) */
    private string $feature;
    /** @ORM\Column(type="string",length=255) */
    private string $city;
    /** @ORM\Column(type="string",length=11) */
    private string $tel;
    /** @ORM\Column(type="string",length=255) */
    private ?string $photo;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    public function __construct(
        int $id = 0,
        string $name="",
        int $age=0,
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

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
