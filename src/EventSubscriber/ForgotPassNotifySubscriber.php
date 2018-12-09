<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 06/12/2018
 * Time: 18:11
 */

namespace App\EventSubscriber;

use Twig\Environment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\ForgotPassEvent;

class ForgotPassNotifySubscriber implements EventSubscriberInterface
{

    private $mailer;
    private $sender;
    private $twig;

    /**
     * ForgotPassNotifySubscriber constructor.
     * @param \Swift_Mailer $mailer
     * @param $sender
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, $sender,  Environment $twig)
    {
        // On injecte notre expediteur, la classe pour envoyer des mails, le rendu twig
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
            ForgotPassEvent::FORGOT_PASS => 'forgot_pass',
        ];

    }

    /**
     * @param ForgotPassEvent $event
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function forgot_pass(ForgotPassEvent $event): void
    {
        $user = $event->getUser();
        $subject = "Mot de passe oublié ?";
        $body = $this->twig->render('user/emailForgotPass.html.twig', compact('user')) ;

        $message = (new \Swift_Message('Réinitialisation'))
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom($this->sender)
            ->setBody($body)
        ;

        $this->mailer->send($message);
    }
}