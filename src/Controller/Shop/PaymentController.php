<?php

namespace App\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Cart\CartService;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function payment(Request $request, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();

        \Stripe\Stripe::setApiKey('sk_test_51GzL7lEJkHBBWUtVumbONpTs5JgFGs16kVRhBAq3GVrsazU6RUiDPk0FAxgXjaZaqD1Q2bQdO8FY6os4DVSjNSdj00iCxtQXeV');

        $intent= \Stripe\PaymentIntent::create([
            'amount' => $cartService->getTotal()*100,
            'currency' => 'eur',
        ]);

        return $this->render('shop/cart/payment.html.twig', [
            'price' => $cartService->getTotal(),
            'num' => $num,
            'intent' => $intent
        ]);
    }
}
