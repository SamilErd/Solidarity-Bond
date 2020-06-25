<?php

namespace App\Controller\More;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;
use App\Entity\Contact;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;

class MoreController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */

    public function contact(Request $request, \Swift_Mailer $mailer, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //creatin a Contact Entity instance
        $contact = new Contact();
        //creating the form with the contact instance using the ContactType template
        $form = $this->createForm(ContactType::class, $contact);
        //handling the form's answer
        $form->handleRequest($request);
        //if the form is submitted without errors
        if ($form->isSubmitted() && $form->isValid()) {
            //creating a new message with the following subject
            $message = (new \Swift_Message('Nouveau message'))
            //getting the author's email
            ->setFrom($contact->getEmail())
            //sending to specific mail
            ->setTo('contact@solidarity-bond.fr')
            //sending reply to author's email
            ->setReplyTo($contact->getEmail())
            //setting the content of the mail with the selected template
            ->setBody($this->renderView('emails/emails_contact.html.twig',[
                //setting the mail's contact info with contact variable
                'contact' => $contact
            ]), 'text/html');
            //sending the message with the mailer
            $mailer->send($message);
            //redirecting to homepage
            return $this->redirectToRoute('index');
        }    

        //rendering the contact page
        return $this->render('more/contact.html.twig', [
            //giving the contact page the contact form variable
            'form' => $form->createView(),
            'num' => $num
        ]);
    }


    /**
     * @Route("/about_fablab", name="about_fablab")
     */
    public function aboutfablab(CartService $cartService){
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //rendering the infos about fablab page
        return $this->render('more/about_fablab.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/about_group", name="about_group")
     */
    public function aboutgroup(CartService $cartService){
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //rendering the infos about the students page
        return $this->render('more/about_group.html.twig', [
            'num' => $num
        ]);
    }
}
