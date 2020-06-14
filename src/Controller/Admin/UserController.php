<?php

namespace App\Controller\Admin;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;



class UserController extends AbstractController
{

    /**
     * @Route("/admin_users", name="admin_users")
     */
    public function admin_users(UserRepository $urepo)
    {
        $users = $urepo->findAll();


        //rendering the admin page for the administrator
        return $this->render('admin/users.html.twig', [
            "users" => $users 
        ]);
    }

}
