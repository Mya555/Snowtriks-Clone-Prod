<?php

namespace App\Controller;

use App\Form\ResetPassType;
use App\Events;
use App\Event\ForgotPassEvent;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Form\ForgotPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Form\FormError;

class UserController extends Controller
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var TokenStorageInterface
     */
    private $token;


    // CONSTRUCTEUR //

    /**
     * UserController constructor.
     * @param AuthenticationUtils $authenticationUtils
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param TokenStorageInterface $token
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils, // Extrait les erreurs de sécurité.
        UserPasswordEncoderInterface $encoder, // L'interface du service de codage de mot de passe.
        EntityManagerInterface $em, // Gère les relations entre entités, sauvegarde & extrait les données de la base.
        EventDispatcherInterface $dispatcher, // Permets aux composants de communiquer entre eux en distribuant des événements et en les écoutant.
        TokenStorageInterface $token) // L'interface pour les informations d'authentification de l'utilisateur.
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->encoder = $encoder;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->token = $token;
    }

    /////////////////
    /// CONNEXION ///
    /////////////////
    /**
     * @Route("/login", name="login")
     * @return Response
     */
    public function login()
    {
        // Afficher l'erreur si il y en a une
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // dernier username saisi (si il y en a un)
        $lastUsername = $this->authenticationUtils->getLastUsername();
        return $this->render( 'user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ) );
    }

    ///////////////////
    /// INSCRIPTION ///
    ///////////////////
    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function register(Request $request)
    {

        // On crée le formulaire
        $user = new User();
        $form = $this->createForm( UserType::class, $user );

        // On gére la soumission du formulaire
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupère l'utilisateur depuis le formulaire
            $user = $form->getData();

            // On encode le mot de passe
            $password = $this->encoder->encodePassword( $user, $user->getPlainPassword() );
            $user->setPassword( $password );
            $user->setIsActive( false );
            $user->setPlainPassword( null );

            //Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles( ['ROLE_USER'] );

            // On enregistre l'utilisateur dans la base
            $em = $this->em;
            $em->persist( $user );
            $em->flush();

            //On crée l'évènement
            $event = new UserCreatedEvent( $user );

            //Et on le déclanche
            $this->dispatcher->dispatch( UserCreatedEvent::NAME, $event );

            return $this->render( 'user/afterRegister.html.twig' );
        }
        return $this->render(
            'user/registration.html.twig',
            array('form' => $form->createView())
        );
    }

    ////////////////////////////
    /// ACTIVATION DU COMPTE ///
    ////////////////////////////
    /**
     * @Route("/activate/{token}", name="activate")
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activate($token)
    {
        // Récupération de l'utilisateur par son token
        $user = $this->em->getRepository( User::class )
            ->findOneBy( ['token' => $token] );

        // Si l'utilisateur n'existe pas, l'erreur est retournée
        if (!$user) {
            throw new NotFoundHttpException( "L'utilisateur n'existe pas" );
        }
        // Activation de l'utilisateur
        $user->setIsActive( true )
             ->setToken( null );

        // On enregistre l'utilisateur dans la base
        $this->em->persist( $user );
        $this->em->flush();

        // L'utilisateur est automatiquement connécté.
        // Pour cela crée le token avec UsernamePasswordToken, on récupèrant le mot de passe lié au $user,
        // en indiquant le farewall à utiliser 'main' pour y passer et en récupèrant le role lié à cet utilisateur.
        $this->token->setToken( new UsernamePasswordToken( $user, $user->getPassword(), 'main', $user->getRoles() ) );

        return $this->redirect( '/' );
    }



         ///////////////////////////
        /// MOT DE PASSE OUBLIE ///
       ///////////////////////////

        /**
         * @Route("/forgot", name="forgot")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
         */
        public function forgotPass(Request $request){

            // Récupération de entityManager
            // Création du formulaire
            // Gestion de la soumission du formulaire
            $em = $this->em;
            $form = $this->createForm(ForgotPasswordType::class);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                // Récupération des données du formulaire
                // Liaison des login du formulaire et celui de l'utilisateur de la base de donnée
                // Si l'utilisateur n'existe pas, l'erreur est retournée
                $username = $form->getData();
                $user = $em->getRepository(User::class)->findOneByUsername($username);
                if (!$user) {
                    throw new NotFoundHttpException( "L'utilisateur n'existe pas" );
                }
                // Encodage du mot de passe
                // Enregistrement de l'utilisateur dans la base
                $user->setResetToken($this->encoder->encodePassword( $user, $user->getResetToken()));
                $em->persist($user);
                $em->flush();

                // Création de l'evenement
                // Déclanchement de l'événement
                $event = new ForgotPassEvent($user);
                $this->dispatcher->dispatch(ForgotPassEvent::FORGOT_PASS, $event);

                return $this->render(
                    'user/confirmEmailPass.html.twig');

        }
            return $this->render(
                'user/forgotPassword.html.twig',
                ['form' => $form->createView()]
            );

        }

         /////////////////////////////////////
        /// REINITIALISER LE MOT DE PASSE ///
       /////////////////////////////////////

        /**
         * @Route("/reset/{token}", name="reset_pass")
         * @param Request $request
         * @param $token
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
         */
        public function resetPassword(Request $request, $token)
        {
            // Récupération de entityManager
            // Liaison des login du token et de l'utilisateur
            // Si l'utilisateur n'existe pas, l'erreur est retournée
            $em = $this->em;
            $user = $em->getRepository(User::class)->findOneByResetToken($token);
            if (is_null($user)) {
                throw new NotFoundHttpException('Utilisateur introuvable');
            }
            // Récupération des données de l'entité user
            // Création du formulaire
            // Gestion de la soumission du formulaire
            $email = $user->getEmail();
            $form = $this->createForm(ResetPassType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // Si l'utilisateur n'existe pas, l'erreur est retournée
                if ($email != $form->getData()) {
                    $form->get('email')->addError(new FormError("Cette adresse est invalide"));
                }
                    // Encodage du mot de passe
                    $password = $this->encoder->encodePassword($user, $form->getData()['password']);
                    $user->setPassword($password);
                    $user->setResetToken(null);
                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('homepage');
            }
            return $this->render(
                'user/resetPassword.html.twig',
                ['form' => $form->createView()]
            );
        }


      ///////////////////////
     /// AJOUT DE AVATAR ///
    ///////////////////////
    /**
     * @Route("/avatar/{id}", name="update_user")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function avatarUpload(Request $request, $id)
    {
        // On récupère l'utilisateur par son id
        $em = $this->getDoctrine()->getRepository( User::class );
        $user = $em->find( $id );

        // On crée le formulaire d'édition à partir de UserEditType
        $form = $this->get( 'form.factory' )->create( UserEditType::class, $user );

        // On gére la soumission du formulaire
        $form->handleRequest( $request );

        if ($form->isSubmitted()) {

            // On récupère le tableau des avatars de l'utilisateur
            $file = $request->files->get( 'user_edit' )['avatar_file'];

            // On hashe le nom & on concatène avec guessExtension qui devine et rajoute l'extension du fichier chargé
            $fileName = md5( uniqid() ) . '.' . $file->guessExtension();

            // On définie le dossier dans le quel sera stocké le fichier,
            // enregistré ceci (img_directory) est le nom du parametre qui contient le nom du dossier ou sont stocké les images
            $file->move( $this->getParameter( 'img_directory' ), $fileName );

            //On modifie le nom du fichier de l'avatar
            $user->setAvatar( $fileName );

            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist( $user );
            $em->flush();

            return $this->redirect( $this->generateUrl( 'homepage' ) );
        }
        return $this->render(
            'user/user.html.twig',
            array('form' => $form->createView())
        );
    }
}


