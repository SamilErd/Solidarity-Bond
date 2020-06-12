<?php

namespace App\Controller\More;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;
use App\Entity\Contact;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MoreController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */

    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('succes', 'Votre message a bien été envoyé');

            $message = (new \Swift_Message('Nouveau Message'))
            ->setFrom($contact->getEmail())
            ->setTo('contact@solidaritybond-stras.yj.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderView('emails/emails_contact.html.twig',[
                'contact' => $contact
            ]), 'text/html');
            $mailer->send($message);

            return $this->redirectToRoute('index');
        }    


        return $this->render('more/contact.html.twig', [
            'form' => $form->createView()
        ]);
        /**
         * 
         */
    }


    /**
     * @Route("/about_fablab", name="about_fablab")
     */
    public function aboutfablab(){
        return $this->render('more/about_fablab.html.twig');
    }

    /**
     * @Route("/about_group", name="about_group")
     */
    public function aboutgroup(){
        return $this->render('more/about_group.html.twig');
    }
}
