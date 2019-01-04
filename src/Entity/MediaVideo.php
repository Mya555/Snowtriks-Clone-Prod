<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @property \DateTimeInterface date
 * @ORM\Table(name="media_video")
 * @ORM\Entity(repositoryClass="App\Repository\MediaVideoRepository")
 * @ORM\HasLifecycleCallbacks // Permet d’utiliser des événements
 */
class MediaVideo
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identif;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\tricks", inversedBy="mediaVideos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="#^(http|https):\/\/(www.youtube.com|www.dailymotion.com|vimeo.com)\/#",
     *     match=true,
     *     message="L'url doit correspondre à l'url d'une vidéo Youtube ou DailyMotion"
     * )
     */
    private $url;



    /********** GETTERS & SETTERS **********/


    /**
     * @return mixed
     */
    public function getUrl()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getIdentif()); // on récupère son identifiant

        if($control == 'youtube')
        {
            $embed = "https://www.youtube.com/watch?v=".$id;
            return $embed;
        }
        else if ($control == 'dailymotion')
        {
            $embed = "https://www.dailymotion.com/video/".$id;
            return $embed;
        }
    }

    /**
     * @param $url
     * @return mixed
     */
    public function setUrl($url)
    {
        return $this->url = $url;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MediaVideo
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set identif
     *
     * @param string $identif
     *
     * @return MediaVideo
     */
    public function setIdentif($identif)
    {
        $this->identif = $identif;

        return $this;
    }

    /**
     * Get identif
     *
     * @return string
     */
    public function getIdentif()
    {
        return $this->identif;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return MediaVideo
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return tricks|null
     */
    public function getTrick(): ?tricks
    {
        return $this->trick;
    }

    /**
     * @param tricks|null $trick
     * @return MediaVideo
     */
    public function setTrick(?tricks $trick): self
    {
        $this->trick = $trick;

        return $this;
    }


    /********** AUTRES METHODES **********/

    //Ajouter pour chaque plateforme une fonction pour extraire l’identifiant de la vidéo.


    // YouTube

    /**
     * @param $url
     */
    private function youtubeId($url)
    {
        $tableaux = explode("=", $url);  // découpe l’url en deux  avec le signe ‘=’

        $this->setIdentif($tableaux[1]);  // ajoute l’identifiant à l’attribut identif
        $this->setType('youtube');  // signale qu’il s’agit d’une video youtube et l’inscrit dans l’attribut $type
    }

    // Dailymotion

    /**
     * @param $url
     */
    private function dailymotionId($url)
    {
        $cas = explode("/", $url); // On sépare la première partie de l'url des 2 autres

        $idb = $cas[4];  // On récupère la partie qui nous intéressent

        $bp = explode("_", $idb);  // On sépare l'identifiant du reste

        $id = $bp[0]; // On récupère l'identifiant

        $this->setIdentif($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('dailymotion'); // signale qu’il s’agit d’une video dailymotion et l’inscrit dans l’attribut $type
    }

    // Fonction qui fera le lien entre l’url qui sera reçu lors de l’envoie du formulaire et contenu dans l’attribut $url.
    // Cette fonction sera exécuté avant chaque enregistrement de l’entité en base de donné.

    /**
     * @ORM\PrePersist() // Les trois événement suivant s’exécute avant que l’entité soit enregister
     * @ORM\PreUpdate()
     * @ORM\PreFlush()
     */
    public function extractIdentif()
    {

        $url = $this->url;  // on récupère l’url
        if (preg_match("#^(http|https)://www.youtube.com/#", $url))  // Si c’est une url Youtube on execute la fonction correspondante
        {
            $this->youtubeId($url);
        }
        else if((preg_match("#^(http|https)://www.dailymotion.com/#", $url))) // Si c’est une url Dailymotion on execute la fonction correspondante
        {
            $this->dailymotionId($url);
        }

    }

    // Générer les url des vidéos

    /**
     * @return string
     */
    private  function url()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getIdentif()); // on récupère son identifiant

        if($control == 'youtube')
        {
            $embed = "https://www.youtube-nocookie.com/embed/".$id;
            return $embed;
        }
        else if ($control == 'dailymotion')
        {
            $embed = "https://www.dailymotion.com/embed/video/".$id;
            return $embed;
        }
    }

        // Générer les url des thumbmails

    /**
     * @return string
     */
    public function image()
        {
            $control = $this->getType();  // on récupère le type de la vidéo
            $id = strip_tags($this->getIdentif()); // on récupère son identifiant

            if ($control == 'youtube') {
                $image = 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
                return $image;
            } else if ($control == 'dailymotion') {
                $image = 'https://www.dailymotion.com/thumbnail/150x120/video/' . $id . '';
                return $image;
            }
        }


        // Générer le code d’intégration

    /**
     * @return string
     */
    public function video()
        {
            $video = "<iframe width='100%' height='100%' src='".$this->url()."'  frameborder='0'  allowfullscreen></iframe>";
            return $video;
        }


}
