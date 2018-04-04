<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
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
     * @ORM\ManyToOne(targetEntity="Tricks", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

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
    public function getFile(): File
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return Tricks
     */
    public function getTrick(): Tricks
    {
        return $this->trick;
    }

    /**
     * @param Tricks $trick
     */
    public function setTrick(Tricks $trick): void
    {
        $this->trick = $trick;
    }



}
