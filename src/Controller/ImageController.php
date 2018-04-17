<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tricks;
use App\Entity\TricksType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;



class ImageController extends Controller
{
    /// AJOUTER UNE IMAGE ///
    /**
     * @Route("/ajout", name="image")
     * @param Request $request
     * @param $image
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function imageAdd(Request $request, $image, $id)
    {
        $em = $this->getDoctrine()->getRepository(Tricks::class);
        $trick = $em->find($id);

        $form = $this->get('form.factory')->create(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $files = $request->files->get('trick_image')['images'];
            foreach ($files as $key => $file){
                $filename = $this->generateUniqueFileName().'.'.$file['file']->guessExtension();
                $file['file']->move($this->getParameter('uploads'), $filename);
                $image = new Image();
                $image->setTricks($trick);
                $image->setPath($filename);
                $trick->addImage($image);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('show');
        }
        return $this->render('add.html.twig', [
            'form' => $form->createView(),
            'trick_image' => $trick
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
