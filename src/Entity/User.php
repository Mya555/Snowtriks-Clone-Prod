<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="username", message="Ce nom est déjà pris")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface,  \Serializable
{

    /********** ATTRIBUTS **********/

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "Ce mail '{{ value }}' est invalide.",
     *     checkMX = true,
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Il faut plus de 4 caractères",
     *      maxMessage = "{{ limit }} caractères c'est trop long, Il en faut moins de 255")
     * @Assert\Regex(
     *     pattern = "/^\S+$/",
     *     message = "Les espaces blancs sont interdits")
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user",  cascade={"persist", "remove"})
     */
    private $comments;



    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 4096,
     *      maxMessage = "Wow  mot de passe trop long",
     *      minMessage = "Il faut plus de 6 caractères"
     * )
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     * @Assert\Image()
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;


    /**
     * @var
     */
    private $avatarFile;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isActive;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $resetToken = null;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /********** GETTERS & SETTERS **********/


    /**
     * @return string
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }
    /**
     * @param string $resetToken
     *
     * @return self
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param null|string $token
     * @return User
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param bool $isActive
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments(): Collection
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

    /**
     * @return mixed
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param mixed $avatarFile
     */
    public function setAvatarFile($avatarFile): void
    {
        $this->avatarFile = $avatarFile;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    // le ? signifie que cela peur aussi retourner null
    /**
     * @return null|string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @param Avatar $avatar
     * @return User
     */
    public function addAvatar(Avatar $avatar): self
    {
        if (!$this->avatar->contains($avatar)) {
            $this->avatar[] = $avatar;
            $avatar->setUserAvatar($this);
        }

        return $this;
    }

    /**
     * @param Avatar $avatar
     * @return User
     */
    public function removeAvatar(Avatar $avatar): self
    {
        if ($this->avatar->contains($avatar)) {
            $this->avatar->removeElement($avatar);
            // set the owning side to null (unless already changed)
            if ($avatar->getUserAvatar() === $this) {
                $avatar->setUserAvatar(null);
            }
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }



    /********** AUTRES METHODES **********/



    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * {@inheritdoc}
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * {@inheritdoc}
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        //return false;
        return $this->isActive;
    }
    /**
     * @ORM\PrePersist
     */
    public function generateToken(){
        $this->token = md5(random_bytes(60));
    }
}
