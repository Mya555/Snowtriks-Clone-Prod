<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 10/01/2018
 * Time: 21:51
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

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
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @ORM\Column(type="string")
     */
    protected $user;


    /**
     * Commentaire
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    protected $approved;

    /**
     * @ORM\ManyToOne(targetEntity="Tricks", inversedBy="comments")
     * @ORM\JoinColumn(name="tricks_id", referencedColumnName="id")
     */

    protected $trick;

    /**
     * @ORM\Column("datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true )
     */
    protected $updated;



    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());

        $this->setApproved(true);
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
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param mixed $approved
     */
    public function setApproved($approved): void
    {
        $this->approved = $approved;
    }

    /**
     * @return mixed
     */
    public function getTrick()
    {
        return $this->trick;
    }

    /**
     * @param mixed $trick
     */
    public function setTrick($trick): void
    {
        $this->trick = $trick;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @ORM\PreUpdate
     */

    public function setUpdatedValue()
    {
        $this->setUpdatedValue(new \DateTime());
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyContraint('user', new NotBlank(array(
            'message' => 'Il faut entrer un nom'
        )));

        $metadata->addPropertyContraint('comment', new NotBlank(array(
            'message' => 'Il faut entrer un commentaire'
        )));
    }
    public function __toString()
    {
        return $this->getTitle();
    }


}