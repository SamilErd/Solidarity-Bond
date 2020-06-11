<?php

namespace App\Controller\More;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MoreController extends AbstractController
{


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
