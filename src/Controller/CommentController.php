<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 19/01/2018
 * Time: 10:09
 */

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;


class CommentController extends Controller
{
    public function new($tricks_id)
    {


        $tricks = $this->getTrick($tricks_id);

        $comment = new Comment();
        $comment->setTrick($tricks);
        $form = $this->createForm(new CommentType(), $comment);

        return $this->render('form.html.twig', array(
            'comment' => $comment,
            'form' =>$form->createView()
        ));

    }

    /**
     * @Route("/comment/{blog_id}", name="comment_create", requirements={"_method"="POST", "blog_id"="\d+"})
     */
    public function create($tricks_id)
    {
        $tricks = $this->getTrick($tricks_id);

        $comment = new Comment();
        $comment->setTrick($tricks);
        $request = $this->getRequest();
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);

        if ($form->isValid())
        {
            return $this->redirect($this->generateUrl('show', array(
                'id' => $comment->getTrick()->getId())) .
                '#comment-' . $comment->getId()
            );
        }
        return $this->render('create.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView()
            ));

        if ($form->isValid())
        {
            $em = $this->getDoctrine()
                       ->getEntityManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('show', array(
                'id' => $comment->getTrick()->getId()
            )) .
                '#comment-' . $comment->getId());
        }
    }

    protected function getTrick($blog_id)
    {
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $tricks = $em->getRepository('Tricks')->find($blog_id);

        if (!$tricks) {
            throw $this->createNotFoundException('Figure Introuvable.');
        }

        return $tricks;
    }

}