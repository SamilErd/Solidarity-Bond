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
     * @Route("/account/", name="security_account")
     */
    public function account( Request $request, UserPasswordEncoderInterface $encoder, OrderRepository $orepo)
    {
        
        $error_edit ="";
        $user = $this->getUser();
        //Gets the actual user
        $entityManager = $this->getDoctrine()->getManager();
        $id = $user->getId();
        $orders = $orepo->OrderOfUser($id);
        $password = $user->getPassword();

        if(!empty($_POST["FN"])){
            $user->setFirstName($_POST['FN']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        if(!empty($_POST["LN"])){
            $user->setLastName($_POST['LN']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        if(!empty($_POST["E"])){
            $user->setEmail($_POST['E']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        if(!empty($_POST["PN"])){
            $user->setPhoneNumber($_POST['PN']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        if(!empty($_POST["S"])){
            $user->setStreet($_POST['S']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        if(!empty($_POST["CP"])){
            $user->setPostalCode($_POST['CP']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        if(!empty($_POST["C"])){
            $user->setCountry($_POST['C']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        
            
        


        
        if(!empty($_POST['CurrentPass'])){
            $old_pwd = $_POST['CurrentPass'];
            $new_pwd = $_POST['NewPass'];
            $con_pwd = $_POST['ConfirmPass'];
            $checkPass = $encoder->isPasswordValid($user, $old_pwd);
            if($checkPass == true){
                if($new_pwd === $con_pwd){
                    
                    $user->setPassword($encoder->encodePassword($user , $new_pwd));
                    //tells the entity manager to manage the user
                    $entityManager->persist($user);
                    //basically updating the user infos in the database
                    $entityManager->flush();
                    //redirecting the user to the security_update
                    return $this->redirectToRoute('security_account');

                } else {
                    $error_edit = " Vos mots de passes ne correspondent pas.";
                }
            } else {
                $error_edit = " Votre mot de passe actuel est incorrect.";
            }
            $error_edit="";
        }
               

          
        
        //rendering the user account's page
        return $this->render('security/account.html.twig', [
            //giving the account page the form variable and the user id
            'orders' => $orders,
            'error_edit' => $error_edit,
        ]);
    }

    
}
