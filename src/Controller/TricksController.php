<?php

namespace App\Controller;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Entity\Tricks;
use App\Form\CommentEditType;
use App\Form\CommentType;
use App\Form\TricksType;
use App\Form\TricksEditType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class TricksController extends Controller
{
    /**
     * Affichage d'une figure
     * @Route("/figure/{id}", name="show")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function show(EntityManagerInterface $em, $page = 1, Request $request ,$id)

    {
        /* Affichage de la figure */

        $trick = $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository(Tricks::class)
        ->find($id);

        /* Création du commentaire lié à la figure affichée */

        $comment = new Comment();
        $comment->setTricks($trick);
        $form = $this->get('form.factory')->create(CommentType::class, $comment);



        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $user = $this->getUser();


            if (null === $user) {
                // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
            } else {
                // Ici, $user est une instance de notre classe User
            }

            return $this->redirectToRoute('show', array('id' => $trick->getId($id)));
        }

        if (!$trick) {

            throw new NotFoundHttpException(
                'Aucun résultat ne correspond à votre recherche'
            );
        }
        /** @var TYPE_NAME $this */
        return $this->render('show.html.twig', array('trick' => $trick,  'form' => $form->createView(), 'id' => $trick->getId($id), 'comment' => $comment));
    }




    /**
     * Affichage de toutes les figures
     * @Route("/liste", name="list")
     */

        public function list()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Tricks::class)
            ->findAllTricks();


        /** @var TYPE_NAME $tricks */
        foreach ($repository as $tricks) {



            $url = $this->generateUrl('show', array('id' => $tricks->getId()));

        }
        return $this->render('list.html.twig',  array('tricks' => $tricks, 'repository' => $repository ));
    }

    /**
     * Affichage de toutes les figures
     * @Route("/liste_add", name="list_add")
     */

    public function listAdd()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Tricks::class)
            ->findAll();


        foreach ($repository as $tricks) {



            $url = $this->generateUrl('show', array('id' => $tricks->getId()));

        }
        /** @var TYPE_NAME $tricks */
        return $this->render('listAdd.html.twig',  array('tricks' => $tricks, 'repository' => $repository ));
    }


    /**
     * Addition d'une figure
     * @Route("/ajout", name="add")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param AuthorizationCheckerInterface $authChecker
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function add(Request $request, FileUploader $fileUploader, AuthorizationCheckerInterface $authChecker)
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
        throw new AccessDeniedException('Unable to access this page!');
        }

        $trick = new Tricks();
        $form   = $this->get('form.factory')->create(TricksType::class, $trick);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');




            return $this->redirectToRoute('show', array('id' => $trick->getId()));

        }

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Edition d'une figure
     * @Route("/editer/{id}", name="edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $trick = $em->getRepository(Tricks::class)->find($id);

        if (null === $trick) {
            throw new NotFoundHttpException("Cette page n'existe pas");
        }

        /**$images = [];

        foreach ($trick->getImages() as $image) {
            $images[] = new File($this->getParameter('uploads').'/'.$image);
        }

        $trick->setImages(
            $images
        );
         **/

        $form = $this->get('form.factory')->create(TricksEditType::class, $trick);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('show', array('id' => $trick->getId()));
        }

        return $this->render('edit.html.twig', array(
            'trick' => $trick,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Suppression d'une figure
     * @Route("/supprimer/{id}", name="delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();

        $tricks = $em->getRepository(Tricks::class)->find($id);

        if (null === $tricks) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $em->remove($tricks);
        $em->flush();

        return $this->redirectToRoute('list');
    }





}
