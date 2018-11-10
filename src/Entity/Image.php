<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\HasLifecycleCallbacks // Permet d’utiliser des événements
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /********* CONSTANTE **********/

    const PATH_TO_IMAGE = 'uploads';

    /********** ATTRIBUTS **********/

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @var Tricks
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks", inversedBy="images")
     */
    private $tricks;


    /********** GETTERS & SETTERS **********/

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Image
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Tricks
     */
    public function getTricks(): Tricks
    {
        return $this->tricks;
    }

    /**
     * @param Tricks $tricks
     * @return Image
     */
    public function setTricks(Tricks $tricks)
    {
        $this->tricks = $tricks;
        return $this;
    }

    /**
     * @ORM\PrePersist() // Les événements suivant s’exécute avant que l’entité soit enregister
     * @ORM\PreUpdate() // Les événements suivant s’exécute après que l’entité soit enregister
     */
    public function moveImage(){
        $fileName =  md5(uniqid()) . '.' . $this->file->guessExtension();
        // Déplace le fichier dans le répertoire où sont stockées les images
        $this->file->move(self::PATH_TO_IMAGE , $fileName);
        $this->setPath($fileName);
    }
}
