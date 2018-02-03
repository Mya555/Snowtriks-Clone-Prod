<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Tricks;
use App\Form\TricksType;
use App\Form\TricksEditType;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TricksController extends Controller
{
    /**
     * Affichage d'une figure
     * @Route("/figure/{id}", name="show")
     */
    public function show($id)

    {
        $trick = $repository = $this

                ->getDoctrine()
                ->getManager()
                ->getRepository(Tricks::class)
                ->find($id);

        if (!$trick) {

            throw new NotFoundHttpException(
                'Aucun résultat ne correspond à votre recherche'
            );
        }



        return $this->render('show.html.twig', array('trick' => $trick));
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
            ->findAll();


        foreach ($repository as $tricks) {



            $url = $this->generateUrl('show', array('id' => $tricks->getId()));

        }
        return $this->render('list.html.twig',  array('tricks' => $tricks, 'repository' => $repository ));
    }


    /**
     * Addition d'une figure
     * @Route("/ajout", name="add")
     */
    public function add(Request $request, FileUploader $fileUploader)
    {
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






}
