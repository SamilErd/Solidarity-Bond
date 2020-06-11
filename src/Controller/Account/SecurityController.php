<?php

namespace App\Controller\Account;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder )
    {


        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'error' => $error,
        ]);
    }


    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout() {}


    /**
     * @Route("/account", name="security_account")
     */
    public function account(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $thisuser = $this->getUser();
        $id = $thisuser->getId();
        $form = $this->createForm(RegisterType::class, $thisuser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($id);

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('security_update');
        }




        return $this->render('security/account.html.twig', [
            'form' => $form->createView(),
            'id' => $id,

        ]);
    }

        /**
     * @Route("/update", name="security_update")
     */
    public function update(Request $request){
    

        $form = $this->createForm(RegisterType::class, $this->getUser());
        $form->handleRequest($request);
        return $this->render('security/account.html.twig', [
            'form' => $form->createView(),
            'updatemessage' => "Votre compte a été mis à jour."
        ]);
    }

}
