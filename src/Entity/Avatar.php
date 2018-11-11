<?php

namespace App\Entity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 */
class Avatar
{
    /********** ATTRIBUTS **********/

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="avatar")
     */
    private $user_avatar;



    /********** GETTERS & SETTERS **********/

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Avatar
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return Avatar
     */
    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUserAvatar(): ?User
    {
        return $this->user_avatar;
    }

    /**
     * @param User|null $user_avatar
     * @return Avatar
     */
    public function setUserAvatar(?User $user_avatar): self
    {
        $this->user_avatar = $user_avatar;

        return $this;
    }
}
