# Snowtricks-Projet-6

Pour installer le projet sur le client vous devez vous rendre sur le lien suivant : https://github.com/Mya555/Snowtricks-Projet-6. Après avoir copié l'URL du projet qui apparaît lorsque vous appuyez sur le bouton "clone or download", rendez-vous dans l'invite de commande git, ouverte à l'endroit où vous souhaitez placer le projet. Utilisez les commande "git init" suivis de "git clone + 'URL du projet'". L'installation est terminée.






    /**
     * @ORM\nom
     * @ORM\Column(type="string", length=255, name="nom")
     */
    private $nom;



    /**
     * @ORM\groupe
     * @ORM\Column(type="string", length=255, name="groupe")
     */
    private $groupe;



    /**
     * @ORM\description
     * @ORM\Column(type="string", length=1000, name="description")
     */
    private $description;



    /**
     * @ORM\dateCreation
     * @ORM\Column(type="datetime", name="date_creation")
     */
    private $dateCreation;



    // Les getteurs



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
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }


    //Les setteurs



    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @param mixed $groupe
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }







class TricksController extends Controller
{
    /**
     * @Route("/accueil", name="tricks")
     */
    public function show()

    {
        $em = $this->getDoctrine()->getManager();

        $trick = new Tricks();
        $trick->setName('Figure de ouf!');
        $trick->setDescription('description');
        $trick->setGroup('group');
        $trick->getId();
        $trick->setDateCreation('now');


        $em->persist($trick);
        $em->flush();

        return new Response($trick->getName());
    }
}return new Response('Le nom est - ' .$trick->getName(). ' <br/>La description est - ' .$trick->getDescription(). '<br/>Le groupe est - ' .$trick->getGroupe(). '<br/> ');