<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{

    /**
     * @Route("/error", name="show")
     */
    public function show(){
        //rendering the infos about fablab page
        return $this->render('error/error404.html.twig');
    }

    
}
