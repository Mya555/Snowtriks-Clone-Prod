<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TricksType;
use App\Form\TricksEditType;
use App\src\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TricksController extends Controller
{
    /**
     * Affichage d'une figure
     * @Route("/figure/{id}", name="show")
     */
    public function show($id)

    {

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Tricks::class);


        $trick = $repository->find($id);



        if (!$trick) {

            throw new NotFoundHttpException(
                'Aucun résultat ne correspond à votre recherche'
            );
        }





        return $this->render('show.html.twig', array('trick' => $trick));
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


        if ($form->isSubmitted() && $form->isValid()) {
        $file = $trick->getImage();
        $fileName = $fileUploader->upload($file);

        $trick->setImage($fileName);


        return $this->redirectToRoute('show', array('id' => $trick->getId()));
    }
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
