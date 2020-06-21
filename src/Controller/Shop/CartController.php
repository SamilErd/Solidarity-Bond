<?php

namespace App\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(CartService $cartService)
    {
        //redering the cart page with it's variables
        return $this->render('shop/cart/cart.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }
    /**
     * @Route("/cart/add/{id}_{quantity}", name="cart_add")
     */
    public function add($id, $quantity, CartService $cartService)
    {

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_ANONYMOUS')) {
            return $this->redirectToRoute("security_login");
        }

        //adding a product in the cart, with it's quantity
        $cartService->add($id, $quantity);
        return $this->redirectToRoute("show_products");
    }


    /**
     * @Route("/cart/remove/{id}/{url}", name="cart_remove")
     */

    public function remove($id,$url, CartService $cartService){
        //removing a product from the cart
        $cartService->remove($id);
        return $this->redirectToRoute($url);
    }
}
