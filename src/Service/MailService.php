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
    public function sendToken($user)
    {
        

        $this->mailer->send($message);
    }

}