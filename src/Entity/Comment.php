<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 30/01/2018
 * Time: 20:11
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository")
 */
class Comment
    {
        /**
     * Date de la creation du commentaire
     * @ORM\Column(name="dateCom", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCom;

    /**
     * @return \Datetime
     */
    public function getDateCom(): \Datetime
    {
        return $this->dateCom;
    }

    /**
     * @param \Datetime $dateCom
     */
    public function setDateCom(\Datetime $dateCom): void
    {
        $this->dateCom = $dateCom;
    }

    public function __construct()
    {
        $this->dateCom = new \Datetime();
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tricks;

    /**
     * @return mixed
     */
    public function getTricks()
    {
        return $this->tricks;
    }

    /**
     * @param mixed $tricks
     */
    public function setTricks($tricks): void
    {
        $this->tricks = $tricks;
    }



    /**
     * @ORM\Column(type="string", length=100)
     */
    private $author;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text")
     */
    private $comment;


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

    /**

     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }



}