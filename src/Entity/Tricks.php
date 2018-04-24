<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
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
    /********** ATTRIBUTS **********/

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Nom de la figure
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * La description de la figure
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var Collection
     * @Assert\Image()
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="user", cascade={"persist"})
     */
    private $images;



    private $imageFile;



    /**
     * Video représentant la figure
     * @ORM\Column(type="simple_array", name="video", nullable=true)
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="tricks", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * Auteur de la figure
     * * @ORM\Column(type="string", length=255, name="user")
     */
    private $user;

    /**
     * Date de la creation de la figure
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * Le groupe de la figure
     * @ORM\Column(type="string", length=255)
     */
    private $groupe;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /********** CONSTRUCTOR **********/

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
    }


    /********** GETTERS & SETTERS **********/

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @return Collection
     */
    public function getImages()
    {
        return $this->images;
    }



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
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
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

    /**
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param mixed $videos
     */
    public function setVideos($videos): void
    {
        $this->videos = $videos;
    }


    /********** AUTRES METHODES **********/

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * @param Image $image
     */
    public function addImage(Image $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }
    }


    /**
     * @param Image $image
     * @return $this
     */
    public function removeImage(Image $image)
    {
            $this->images->removeElement($image);

        return $this;
    }

}
