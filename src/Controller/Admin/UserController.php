<?php

namespace App\Controller\Admin;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Service\Cart\CartService;



class UserController extends AbstractController
{

    /**
     * @Route("/admin_users", name="admin_users")
     */
    public function admin_users(UserRepository $urepo, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        $users = $urepo->findAll();
        
        //rendering the admin page for the administrator
        return $this->render('admin/users.html.twig', [
            "users" => $users ,
            'num' => $num
        ]);
    }
    
    /**
     * @Route("/test/{id}", name="test")
     */
    public function test($id, UserRepository $urepo) {
        $user = $urepo->find($id);        
        dd($user->getToken());
    }

}
