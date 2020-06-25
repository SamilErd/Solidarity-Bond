<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //redering the homepage
        return $this->render('main/index.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/privacy", name="privacy_policy")
     */
    public function privacy(CartService $cartService){
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //rendering the privacy policy
        return $this->render('legal/privacy.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/legal", name="legal_notice")
     */
    public function legal(CartService $cartService){
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //rendering the legal notice
        return $this->render('legal/legal_notice.html.twig', [
            'num' => $num
        ]);
    }
}
