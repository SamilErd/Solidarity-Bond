<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        //redering the homepage
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/privacy", name="privacy_policy")
     */
    public function privacy(){
        //rendering the privacy policy
        return $this->render('legal/privacy.html.twig');
    }
}
