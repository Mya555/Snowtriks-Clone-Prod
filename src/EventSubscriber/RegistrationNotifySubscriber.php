<?php
// App\EventSubscriber\RegistrationNotifySubscriber.php
namespace App\EventSubscriber;


use App\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur
 *
 */
class RegistrationNotifySubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $sender;
    private $twig;

    /**
     * RegistrationNotifySubscriber constructor.
     * @param \Swift_Mailer $mailer
     * @param $sender
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, $sender,  Environment $twig)
    {
        // On injecte notre expediteur et la classe pour envoyer des mails
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->twig = $twig;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::NAME => 'onUserRegistrated',
        ];
    }

    /**
     * @param UserCreatedEvent $event
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserRegistrated(UserCreatedEvent $event): void
    {
        /* $user = $event->getSubject(); */

        $user = $event->getUser();
        $subject = "Bienvenue";
        $body = $this->twig->render('user/emailRegistration.html.twig', compact('user'));


        $message = (new \Swift_Message('Registration'))
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom($this->sender)
            ->setBody($body,'text/html')
        ;
        $this->mailer->send($message);
    }
}