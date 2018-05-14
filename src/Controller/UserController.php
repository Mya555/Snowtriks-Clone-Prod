<?php

namespace App\Controller;

use App\Entity\Avatar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // Afficher l'erreur si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // dernier username saisi (si il y en a un)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }


    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);

            // 4) On enregistre l'utilisateur dans la base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('list_add');
        }

        return $this->render(
            'registration.html.twig',
            array('form' => $form->createView())
        );

    }

    /**
     * @Route("/user/{id}", name="update_user")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function avatarUpload(Request $request, $id)
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $user = $em->find($id);

        $form = $this->get('form.factory')->create(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $file = $request->files->get('user_edit')['avatar_file'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move($this->getParameter('img_directory'), $fileName);

            $user->setAvatar($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('list_add'));
    }


        return $this->render(
            'user.html.twig',
            array('form' => $form->createView())
        );

    }
}


