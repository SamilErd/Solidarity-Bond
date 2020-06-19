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
use App\Repository\OrderRepository;

class SecurityController extends AbstractController
{

    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder )
    {

        //Creating a User instance
        $user = new User();
        //Creating a form with the form created in RegisterType linked to user entity
        $form = $this->createForm(RegisterType::class, $user);
        //handling the form's response
        $form->handleRequest($request);
        //if the form is submitted without errors
        if ($form->isSubmitted() && $form->isValid()) {
            //setting the default role to any users
            $user->setRoles(["ROLE_USER"]);
            //encoding the user's password with the algorithm set in security.yml in config/packages
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            //getting the instance of the entity manager
            $entityManager = $this->getDoctrine()->getManager();
            //telling the entity manager to manage the user 
            $entityManager->persist($user);
            //basically inserting the user in the database
            $entityManager->flush();
            //after creting the user, redirecting onto the login page
            return $this->redirectToRoute('security_login');
        }
        //rendering the register page
        return $this->render('security/register.html.twig', [
            //giving the register page the form variable
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        //the function itself implicitely tries the login
        //stores the login error in the error variable
        $error = $authenticationUtils->getLastAuthenticationError();
        //rendering the login page if failed
        return $this->render('security/login.html.twig', [
            //giving the login page the error to show it
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
    public function account(Request $request, UserPasswordEncoderInterface $encoder, OrderRepository $orepo)
    {

        //Gets the actual user
        $thisuser = $this->getUser();
        $id = $thisuser->getId();
        $orders = $orepo->OrderOfUser($id);
        //Gets the id of the actual user
        
        //creates a form with the user instance
        $form = $this->createForm(RegisterType::class, $thisuser);
        //handles the answer from the account page's form
        $form->handleRequest($request);
        //if the form is submitted without errors
        if ($form->isSubmitted() && $form->isValid()) {
            //Gets an instance of the entity manager
            $entityManager = $this->getDoctrine()->getManager();
            //Takes the user which will be modified
            $user = $entityManager->getRepository(User::class)->find($id);
            //encode's the new password
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            //tells the entity manager to manage the user
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            
            //redirecting the user to the security_update
            return $this->redirectToRoute('security_account');
        }
        //rendering the user account's page
        return $this->render('security/account.html.twig', [
            //giving the account page the form variable and the user id
            'form' => $form->createView(),
            'orders' => $orders,
        ]);
    }

}
