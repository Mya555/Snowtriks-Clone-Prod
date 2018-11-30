<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use App\Entity\MediaVideo;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Tricks;
use App\Form\CommentEditType;
use App\Form\CommentType;
use App\Form\TricksType;
use App\Form\TricksEditType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @property TokenStorageInterface tokenStorage
 * @property TricksRepository trickRepo
 */
class TricksController extends Controller
{

    /**
     * @var TricksRepository
     */
    private $trickRepo;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    // CONSTRUCTEUR //

    /**
     * TricksController constructor.
     * @param TricksRepository $trickRepo
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TricksRepository $trickRepo, // Récupère le répository de Tricks.
        EntityManagerInterface $entityManager, // Gère les relations entre entités, sauvegarde & extrait les données de la base.
        TokenStorageInterface $tokenStorage // L'interface pour les informations d'authentification de l'utilisateur.
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->trickRepo = $trickRepo;
    }

     ///////////////////////////////////////////////
    /// AFFICHER UNE LISTE DE FIGURES | ACCUEIL ///
   ///////////////////////////////////////////////

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        // $tricks stock toutes les figures récupérées par la variable $trickRepo
        $tricks = $this->trickRepo->findAll();

        return $this->render('index.html.twig',  array('tricks' => $tricks));
    }

     ///////////////////////////
    /// AFFICHER UNE FIGURE ///
   ///////////////////////////

    /**
     * @Route("/figure/{id}", name="show")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function show(Request $request ,$id)
    {
        // $tricks stock toutes les figures récupérées par la variable $trickRepo
        $trick = $this->trickRepo->find($id);

        if (!$trick) {
            throw new NotFoundHttpException( 'Aucun résultat ne correspond à votre recherche' );
        }
        /*
         *  Nouvelle instance de l'objet Comment stocké dans $comment
         *  La methode setTricks lie le commentaire à la figure
         *  La variable $form stock le formulaire créé à partir de CommentType
         */
        $comment = new Comment();
        $comment->setTricks($trick);
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        /*
         *  isMethod() vérifie si la requete est en méthode POST
         *  handleRequest() récupére les valeurs des champs dans les inputs du formulaire
         *  isValide() valide les données saisies
         */
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            /*
             * la variable $comment récupère l'utilisateur connécté avec setAuthor() par son token
             * (persist) Demande au gestionnaire d'entités(entityManager) de suivre les modifications apportées à l'objet
             * (flush) Pousse les modifications des objets d’entités qu’il suit dans la base de données en une seule transaction
             */
            $comment->setAuthor($this->tokenStorage->getToken()->getUser());
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('show', array('id' => $trick->getId($id)));
        }
        return $this->render('trick/show.html.twig', array('trick' => $trick,  'form' => $form->createView(), 'id' => $trick->getId($id), 'comment' => $comment));
    }

     //////////////////////////
    /// AJOUTER UNE FIGURE ///
   //////////////////////////

    /**
     * @Route("/ajout", name="add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function add(Request $request)
    {
        /*
         * $trick stock la nouvelle instance de l'objet Tricks
         * $form stock le nouveau formulaire créé avec createForm() via TricksType
         * handleRequest() récupére les valeurs des champs dans les inputs du formulaire via la variable $request
         */
        $trick = new Tricks();
        $form   = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        /*
        *  isMethod() vérifie si la requete est en méthode POST
        *  isValide valide les données saisies
        */
        if ($form->isSubmitted() && $form->isValid()){
            /*
             * (em) recupère $entityManager pour gerer l'entrer des informations dans la base de donnée
             * (persist) Demande au gestionnaire d'entités(entityManager) de suivre les modifications apportées à l'objet
             * (flush) Pousse les modifications des objets d’entités qu’il suit dans la base de données en une seule transaction
             */
            $em = $this->entityManager;
            $em->persist($trick);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('show', array('id' => $trick->getId(), 'trick' => $trick));
        }
        return $this->render('trick/add.html.twig', array(
            'form' => $form->createView()));
    }

     /////////////////////////
    /// EDITER UNE FIGURE ///
   /////////////////////////

    /**
     * @Route("/editer/{id}", name="edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit( $id, Request $request)
    {
        // $trick stock toutes les figures récupérées par la variable $trickRepo
        $trick = $this->trickRepo->find($id);

        if (null === $trick) {
            throw new NotFoundHttpException("Cette page n'existe pas");}


        $form = $this->createForm(TricksEditType::class, $trick);
        $form->handleRequest($request);

        /*
        *   isSubmitted() vérifie si le formulaire est soumis
        *  isValide valide les données saisies
        */
        if ($form->isSubmitted() && $form->isValid()) {

            /*
            * (persist) Demande au gestionnaire d'entités(entityManager) de suivre les modifications apportées à l'objet
            * (flush) Pousse les modifications des objets d’entités qu’il suit dans la base de données en une seule transaction
            */
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('show', array('id' => $trick->getId()));
        }
        return $this->render('trick/edit.html.twig', array(
            'trick' => $trick,
            'form'   => $form->createView(),
        ));
    }

     ////////////////////////////
    /// SUPPRIMER UNE FIGURE ///
   ////////////////////////////

    /**
     * @Route("/supprimer/{id}", name="delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        /*
         * (em) stock le gestionnaire d'entités (entityManager)
         * (tricks) stock la liste des figure récupérées avec getRepositoty()
         */
        $em = $this->entityManager;
        $tricks = $em->getRepository(Tricks::class)->find($id);

        if (null === $tricks) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        /*
        *  remove vas supprimer la figure de la base de donnée à l'aide entityManager stocké dans (em)
        *  (flush) Pousse les modifications des objets d’entités qu’il suit dans la base de données en une seule transaction
        */
        $em->remove($tricks);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }


    /// SUPPRIMER UNE IMAGE ///
    /**
     * @Route("/supprimerImage/{id}", name="deleteImage")
     * @param Image $image
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImage(Image $image, Request $request){
        if (null === $image) {
            throw new NotFoundHttpException("Imposible de supprimer la vidéo.");
        }
        $em = $this->entityManager;
        $em->remove($image);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'L\'image a bien été supprimée.');
        return $this->redirectToRoute('edit', ['id' => $image->getTricks()->getId()]);
    }


    /// SUPPRIMER UNE VIDEO ///

    /**
     * @Route("/supprimerVideo/{id}", name="deleteVideo")
     * @param MediaVideo $mediaVideo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteVideo(MediaVideo $mediaVideo, Request $request){

        if (null === $mediaVideo) {
            throw new NotFoundHttpException("Imposible de trouver la vidéo.");
        }
        $em = $this->entityManager;
        $em->remove($mediaVideo);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'La vidéo a bien été supprimée.');

        return $this->redirectToRoute('edit', ['id' => $mediaVideo->getTrick()->getId()]);
    }
}