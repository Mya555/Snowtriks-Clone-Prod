<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tricks
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="tricks", cascade={"persist", "remove"})
     * @Assert\Count(
     *      min = 1,
     *      max = 5,
     *      minMessage = "tricks.images.min",
     *      maxMessage = "tricks.images.max"
     * )
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $images;



    /**
     * @param mixed $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="tricks", cascade={"persist", "remove"})
     */
    private $comments;


    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }
    //constructeur des figures

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->images = new ArrayCollection();

        $this->comments = new ArrayCollection();

    }

    /**
     * Add image
     *
     *
     * @param Image $image
     * @return Tricks
     */
    public function addImage(Image $image)
    {
        if(!$this->images->contains($image))
        {
            $this->images[] = $image;
        }
        $image->setTricks($this);
        return $this;
    }

    /**
     * Remove image
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }
    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }






    /**
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * Nom de la figure
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }





    /**
     * La description de la figure
     * @ORM\Column(type="text")
     */

    private $description;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }





    /**
     * Le groupe de la figure
     * @ORM\Column(type="string", length=255)
     */
    private $groupe;

    /**
     * @return mixed
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param mixed $groupe
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
    }




    /**
     * Date de la creation de la figure
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



    /**
     * Auteur de la figure
     * * @ORM\Column(type="string", length=255, name="user")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }










}
