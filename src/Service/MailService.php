<?php
namespace App\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class MailService extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @param $user
     */
    public function newUser($user)
    {
        $message = (new \Swift_Message('Nouvel utilisateur crée.'))
            //getting the author's email
            ->setFrom($user->getEmail())
            //sending to specific mail
            ->setTo('contact@solidarity-bond.fr')
            //sending reply to author's email
            ->setReplyTo($user->getEmail())
            //setting the content of the mail with the selected template
            ->setBody($this->renderView('emails/emails_register.html.twig',[
                //setting the mail's contact info with contact variable
                'user' => $user
            ]), 'text/html');
            //sending the message with the mailer
        $this->mailer->send($message);
    }
    /**
     * @param $user
     */
    public function sendToken($user, $token)
    {
        $message = (new \Swift_Message('Votre inscription sur Solidarity-bond.fr.'))
            //getting the author's email
            ->setFrom('contact@solidarity-bond.fr')
            //sending to specific mail
            ->setTo($user->getEmail())
            //sending reply to author's email
            ->setReplyTo($user->getEmail())
            //setting the content of the mail with the selected template
            ->setBody($this->renderView('emails/emails_register.html.twig',[
                //setting the mail's contact info with contact variable
                'token' => $token,
                'user' => $user
            ]), 'text/html');
            //sending the message with the mailer
        $this->mailer->send($message);
    }
    /**
     * @param $user
     */
    public function sendTokenPass($user, $token)
    {
        $message = (new \Swift_Message('Demande de reinitialisation du mot de passe.'))
            //getting the author's email
            ->setFrom('contact@solidarity-bond.fr')
            //sending to specific mail
            ->setTo($user->getEmail())
            //sending reply to author's email
            ->setReplyTo($user->getEmail())
            //setting the content of the mail with the selected template
            ->setBody($this->renderView('emails/emails_pass.html.twig',[
                //setting the mail's contact info with contact variable
                'token' => $token,
                'user' => $user
            ]), 'text/html');
            //sending the message with the mailer
        $this->mailer->send($message);
    }

}