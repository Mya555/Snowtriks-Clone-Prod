<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tricks
{


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




    /**
     * Image représentant la figure
     * @ORM\Column(type="simple_array", name="images", nullable=true)
     *
     */


    protected $images = [];


// Cela renvoi un tableau contenant des objets file
    public function getImages()
    {
        $images =[];



        foreach ($this->images as $image)
        {
            if (!$image instanceof UploadedFile){
            $images[]= new File(__DIR__.'\..\..\public\uploads\\' . $image);
            }
            else
            {
                $images[]= $image;
            }
        }

        return $images;
    }

    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }




    /**
     * Video représentant la figure
     * @ORM\Column(type="simple_array", name="video", nullable=true)
     */
    private $videos;

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


    /**
     * Commentaires
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="tricks", cascade={"persist"})
     */
    protected $comments;

    /**
     * @return mixed
     */

    //constructeur des figures

    public function __construct()
    {
        $this->date = new \Datetime();

        $this->comments = new ArrayCollection();









    }



    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    public function addTrick(Tricks $tricks)
    {
        if (!$this->->contains($tricks)) {
            $this->tricks->add($tricks);
        }
    }

    public function addImage(Tricks $images)
    {
        $images->addTricks($this);

        $this->images->add($images);
    }

    public function removeImages(Tricks $images)
    {

    }


}
