<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 15/03/2018
 * Time: 22:47
 */

namespace App\Controller;


class UserController
{

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('list');
        }

        return $this->render(
            'registration.html.twig',
            array('form' => $form->createView())
        );
    }
}