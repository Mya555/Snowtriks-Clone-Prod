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
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
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
        /* Récuperation de toutes les figures */
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
        /* Récuperation de la figure triées par $id */
        $trick = $repository = $this->trickRepo->find($id);
        //Si figure introuvable
        if (!$trick) {
            throw new NotFoundHttpException( 'Aucun résultat ne correspond à votre recherche' );
        }
        /* Création du commentaire lié à la figure affichée */
        $comment = new Comment();
        $comment->setTricks($trick);
        //Récuperation de l'utilisateur connecté
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $comment->setAuthor($this->tokenStorage->getToken()->getUser());
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('show', array('trick' => $trick));
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
        // CREATION DE NOUVELLE FIGURE
        $trick = new Tricks();
        $form   = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Suppression automatique des wigdets restés vides pour images et videos
            foreach ($trick->getImages() as $image){
                if(!$image->getFile()) {
                    $trick->getImages()->removeElement( $image );
                }else{
                $image->setTricks($trick);
                $this->entityManager->persist($image);
                }
            }
            foreach ($trick->getMediaVideos() as $video){
                if (!$video->getUrl()){
                    $trick->getMediaVideos()->removeElement($video);
                }
            }

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
        // Récupération des tricks pour l'affichage de multimédia associée

        $trick = $this->trickRepo->find($id);

        if (null === $trick) {
            throw new NotFoundHttpException("Cette page n'existe pas");}


        $form = $this->createForm(TricksEditType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Suppression automatiques des wigdet resté vides pour images et videos
            foreach ($trick->getImages() as $image){
                if(!$image->getFile()) {
                    $trick->getImages()->removeElement( $image );
                }else{
                    $image->setTricks($trick);
                    $this->entityManager->persist($image);
                }
            }
            foreach ($trick->getMediaVideos() as $video){
                if (!$video->getUrl()){
                    $trick->getMediaVideos()->removeElement($video);
                }
            }

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
        /* Récuperation de la figure */
        $em = $this->entityManager;
        $tricks = $em->getRepository(Tricks::class)->find($id);
        if (null === $tricks) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $em->remove($tricks);
        $em->flush();


        return $this->redirectToRoute('homepage');
    }

     ///////////////////////////
    /// SUPPRIMER UNE IMAGE ///
   ///////////////////////////

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

        return $this->redirectToRoute('show', ['id' => $image->getTricks()->getId()]);
    }

     ////////////////////////////
    /// SUPPRIMER UNE VIDEO ////
   ////////////////////////////

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

        return $this->redirectToRoute('show', ['id' => $mediaVideo->getTrick()->getId()]);
    }
}
