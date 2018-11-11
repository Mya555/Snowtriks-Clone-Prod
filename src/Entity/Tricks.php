<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;
    /**
     * La description de la figure
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="tricks", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @var
     */
    private $imageFile;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediaVideo", mappedBy="trick", orphanRemoval=true)
     */
    private $mediaVideos;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="tricks", cascade={"persist", "remove"} )
     */
    private $comments;
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
        $this->mediaVideos = new ArrayCollection();
    }
    /********** GETTERS & SETTERS **********/
    /**
     * @param string $images
     */
    public function setImages(string $images): void
    {
        $this->images = $images;

    }
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
    public function getImages(): Collection
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
     * @return Collection|MediaVideo[]
     */
    public function getMediaVideos(): Collection
    {
        return $this->mediaVideos;
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
     * @return Tricks
     */
    public function addImage(Image $image): self
    {
        $this->images->add($image);
        $image->setTricks($this);
        return $this;
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

    /**
     * @param MediaVideo $mediaVideo
     * @return Tricks
     */
    public function addMediaVideo(MediaVideo $mediaVideo): self
    {
        if (!$this->mediaVideos->contains($mediaVideo)) {
            $this->mediaVideos[] = $mediaVideo;
            $mediaVideo->setTrick($this);
        }
        return $this;
    }

    /**
     * @param MediaVideo $mediaVideo
     * @return Tricks
     */
    public function removeMediaVideo(MediaVideo $mediaVideo): self
    {
        if ($this->mediaVideos->contains($mediaVideo)) {
            $this->mediaVideos->removeElement($mediaVideo);
            // set the owning side to null (unless already changed)
            if ($mediaVideo->getTrick() === $this) {
                $mediaVideo->setTrick(null);
            }
        }
        return $this;
    }
    // Vérification si il y a une image attachée à la figure

    /**
     * @return bool
     */
    public function hasImages(){
        if ($this->getImages()->isEmpty()){
            return false;
        }
            else{
                return true;
            }
        }

    /**
     * @return bool
     */
    public function hasVideos(){
        if ($this->getMediaVideos()->isEmpty()){
            return false;
        }else{
            return true;
        }
        }


    /**
     * Pour image a affichée sur le show - la fonction renvoi soit la première image soit l'image par defaut
     * @return string
     */
    public function getCoverPath(){
        if ($this->getImages()->isEmpty()){
            return 'img/bg.jpg';
        }
        else{
          return 'uploads/' . $this->getImages()->first()->getPath();
        }
    }
}