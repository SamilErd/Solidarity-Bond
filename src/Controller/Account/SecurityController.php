<?php

namespace App\Controller\Account;

use App\Entity\User;
use App\Entity\Token;
use App\Form\RegisterType;
use App\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\MailService;
use App\Repository\OrderRepository;
use App\Service\Cart\CartService;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{

    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, MailService $mailservice, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //Creating a new User 
        $user = new User();
        //Creating a new Token 
        $token = new Token();
        //creating a new time variable
        $time = new \DateTime();
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
            //setting the token settings
            $token->setToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));
            //setting the token's user
            $token->setIdUser($user);
            //setting the token's creation time
            $token->setCreatedAt($time);
            //setting the token's user
            $user->setToken($token);
            //getting the instance of the entity manager
            $entityManager = $this->getDoctrine()->getManager();
            //telling the entity manager to manage the user 
            $entityManager->persist($user);
            //telling the entity manager to manage the token 
            $entityManager->persist($token);
            //basically inserting the user in the database
            $entityManager->flush();
            //creating a new mail
            $mailservice->sendToken($user, $token);
            

            //redirecting to homepage
            //after creting the user, redirecting onto the login page
            return $this->redirectToRoute('security_login', [
                //$msg = "un mail vous a été envoyé, veuillez confirmer votre compte pour vous y connecter."
            ]);
        }
        //rendering the register page
        return $this->render('security/register.html.twig', [
            //giving the register page the form variable
            'form' => $form->createView(),
            'num' => $num
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //the function itself implicitely tries the login
        //stores the login error in the error variable
        $error = $authenticationUtils->getLastAuthenticationError();

        //rendering the login page if failed
        return $this->render('security/login.html.twig', [
            //giving the login page the error to show it
            'error' => $error,
            'num' => $num
        ]);
    }
    /**
     * @Route("/password_forgot", name="security_password_forgot")
     */
    public function password_forgot(AuthenticationUtils $authenticationUtils, MailService $mailservice, CartService $cartService, UserRepository $urepo)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        $error ="";
        $msg = "";
        if(!empty($_POST["email"])){
            $email = $_POST['email'];
            $user = $urepo->findOneByEmail($email);
            if($user != null){
                //creating a new time variable
                $time = new \DateTime();
                $token = new Token();
                $token->setToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));
                //setting the token's user
                $token->setIdUser($user);
                //setting the token's creation time
                $token->setCreatedAt($time);
                //setting the token's user
                $user->setTokenPass($token);
                //getting the instance of the entity manager
                $entityManager = $this->getDoctrine()->getManager();
                //telling the entity manager to manage the user 
                $entityManager->persist($user);
                //telling the entity manager to manage the token 
                $entityManager->persist($token);
                //basically inserting the user in the database
                $entityManager->flush();
                //creating a new mail
                $mailservice->sendTokenPass($user, $token);
                //setting the message for the next page
                $msg = "Une email vous a été envoyé dans votre boite mail avec un lien vous permettant de reinitialiser votre mot de passe";
            } else {
                $error = "Votre adresse email n'éxiste pas sur notre site web.";
            }

        }
        //rendering the login page if failed
        return $this->render('security/passforgot.html.twig', [
            //giving the login page the error to show it
            'error' => $error,
            //giving the msg variable
            'msg' => $msg,
            //giving the login page the error to show it
            'num' => $num
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout() {}

    /**
     * @Route("/validate_mail/{token}", name="validate_mail")
     */
    public function token($token, TokenRepository $trepo) {

        //getting the email's token
        $token = $trepo->getToken($token);
        //getting the entity manager
        $entityManager = $this->getDoctrine()->getManager();
        //telling the entity manager to manage the token 
        $entityManager->remove($token);
        //basically inserting the user in the database
        $entityManager->flush();
        //rendering the login page 
        return $this->redirectToRoute("security_login");
    }
    /**
     * @Route("/password_reset/{token}", name="security_password_reset")
     */
    public function tokenpass($token, TokenRepository $trepo, CartService $cartService, UserPasswordEncoderInterface $encoder) {
        //if the user changes his password
        $error ="";
        $msg ="";
        //setting the password regex
        $passwordregex = '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/';
        $token = $trepo->getToken($token);
        if(!empty($_POST['NewPass'])){
            //getting the new password
            $new_pwd = $_POST['NewPass'];
            //getting the confirmation password
            $con_pwd = $_POST['ConfirmPass'];      
           //getting the entity manager
            $entityManager = $this->getDoctrine()->getManager();
            if($new_pwd === $con_pwd){
                    if(!preg_match($passwordregex,$new_pwd)) {
                    //Sets the error message if the password doesn't contain at least 1 number
                    $error = "Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, et 1 chiffre.";
                    } else {
                    

                    $user = $token->getIdUser();
                    //If the password respects all the rules, setting the user's new password
                    $user->setPassword($encoder->encodePassword($user , $new_pwd));
                    //tells the entity manager to manage the user
                    $entityManager->persist($user);                    
                    //telling the entity manager to remove the token 
                    $entityManager->remove($token);
                    //basically updating the database
                    $entityManager->flush();
                    //redirecting the user to the security_update
                    $msg = "Votre mot de passe a été modifié avec succès.";
                    }
                } else {
                    //Sets the error message if the new password and the confirmation password doesn't match
                    $error = " Vos nouveaux mots de passes ne correspondent pas.";
                }
            } 
        //getting the number of cart items
        $num = $cartService->getCartItemNum();

        //rendering the reset password page
        return $this->render('security/passchange.html.twig', [
            //giving error message
            'error' => $error,
            //giving the message
            'msg' => $msg,

            //giving the login page the error to show it
            'num' => $num,
            'token' => $token
        ]);
    }

    


    /**
     * @Route("/account/", name="security_account")
     */
    public function account( Request $request, UserPasswordEncoderInterface $encoder, OrderRepository $orepo, CartService $cartService, TranslatorInterface $translator)
    {
        $translatedFirstName = $translator->trans('Votre prénom n\'est pas valide.');
        $translatedLastName = $translator->trans('Votre nom n\'est pas valide.');
        $translatedEmail = $translator->trans('Votre email n\'est pas valide.');
        $translatedPostalCode = $translator->trans('Votre code postal n\'est pas valide.');
        $translatedPassword = $translator->trans('Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, et 1 chiffre.');
        $translatedNewPassword = $translator->trans('Vos nouveaux mots de passes ne correspondent pas.');
        $translatedActualPassword = $translator->trans('Votre mot de passe actuel est incorrect.');
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //iniating the error message for the account page
        $error_edit ="";
        //Gets the actual user
        $user = $this->getUser();
        //Getting an instance of the entity manager
        $entityManager = $this->getDoctrine()->getManager();
        //Getting the id of actual user
        $id = $user->getId();
        //Getting all of the user's Orders
        $orders = $orepo->OrderOfUser($id);
        //setting the name regex
        $nameregex = '/^[a-z-]+$/i';
        //setting the phone regex
        $phoneregex = '/^(0|\+33 )[1-9]([-. ]?[0-9]{2} ){3}([-. ]?[0-9]{2})$/i';
        //setting the password regex
        $passwordregex = '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/';
        //setting the postal code regex
        $cpregex = '/^[0-9]{5,5}$/i';
        //if the user changes his first name
        if(!empty($_POST["FN"])){
            $FN = $_POST['FN'];
            preg_match($nameregex, $FN, $matches);
            if(!empty($matches)){
                $user->setFirstName($FN);
                $entityManager->persist($user);
                //basically updating the user infos in the database
                $entityManager->flush();
                return $this->redirectToRoute('security_account');
            } else {
                $error_edit = $translatedLastName;

            }

            
        }
        //if the user changes his last name
        if(!empty($_POST["LN"])){
            $LN = $_POST['LN'];
            preg_match($nameregex, $LN, $matches);
            if(!empty($matches)){
                $user->setLastName($LN);
                $entityManager->persist($user);
                //basically updating the user infos in the database
                $entityManager->flush();
                return $this->redirectToRoute('security_account');
            } else {
                $error_edit = $translatedLastName;

            }

        }
        //if the user changes his email
        if(!empty($_POST["E"])){
            $E = $_POST['E'];

            if (!filter_var($E, FILTER_VALIDATE_EMAIL)) {
                $error_edit = $translatedEmail;
              } else {
                $user->setEmail($E);
                $entityManager->persist($user);
                //basically updating the user infos in the database
                $entityManager->flush();
                return $this->redirectToRoute('security_account');
              }
            

        }
        //if the user changes his phone number
        if(!empty($_POST["PN"])){
            $user->setPhoneNumber($_POST['PN']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        //if the user changes his street
        if(!empty($_POST["S"])){
            $user->setStreet($_POST['S']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        //if the user changes his postal code
        if(!empty($_POST["CP"])){
            $CP = $_POST['CP'];
            preg_match($cpregex, $CP, $matches);
            if(!empty($matches)){
                $user->setPostalCode($CP);
                $entityManager->persist($user);
                //basically updating the user infos in the database
                $entityManager->flush();
                return $this->redirectToRoute('security_account');
            } else {
                $error_edit = $translatedPostalCode;
            }
        }
        //if the user changes his country
        if(!empty($_POST["C"])){
            $user->setCountry($_POST['C']);
            $entityManager->persist($user);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('security_account');

        }
        //if the user changes his password
        if(!empty($_POST['CurrentPass'])){
            //getting the user's password
            $old_pwd = $_POST['CurrentPass'];
            //getting the new password
            $new_pwd = $_POST['NewPass'];
            //getting the confirmation password
            $con_pwd = $_POST['ConfirmPass'];
            //check if the user's password is correct
            $checkPass = $encoder->isPasswordValid($user, $old_pwd);            
            if($checkPass == true){
                //check if the new password and the confirmation password matches
                if($new_pwd === $con_pwd){
                        if(!preg_match($passwordregex,$new_pwd)) {
                        //Sets the error message if the password doesn't contain at least 1 number
                        $error_edit = $translatedPassword;
                        } else {
                        //If the password respects all the rules, setting the user's new password
                        $user->setPassword($encoder->encodePassword($user , $new_pwd));
                        //tells the entity manager to manage the user
                        $entityManager->persist($user);
                        //basically updating the user infos in the database
                        $entityManager->flush();
                        //redirecting the user to the security_update
                        return $this->redirectToRoute('security_account');
                        }
                    } else {
                        //Sets the error message if the new password and the confirmation password doesn't match
                        $error_edit = $translatedNewPassword;
                        }
                } else {
               //Sets the error message if the user's password is wrong
                $error_edit = $translatedActualPassword;
                }
        }
        //rendering the user account's page
        return $this->render('security/account.html.twig', [
            //giving the account page the user's orders
            'orders' => $orders,
            //giving the error message to the account page
            'error_edit' => $error_edit,
            'num' => $num
        ]);
    }
}
