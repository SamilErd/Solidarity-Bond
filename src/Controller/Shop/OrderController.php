<?php

namespace App\Controller\Shop;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Entity\Product;
use App\Entity\Order;
use App\Service\Cart\CartService;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderController extends AbstractController
{

    /**
     * @Route("/shop/show_order", name="show_order")
     */
    public function show_order(CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //redering the order panel with the users cart
        return $this->render('shop/order/order.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'num' => $num
        ]);
    }

    /**
     * @Route("/shop/order_product", name="order_product")
     */
    public function order_product( ProductRepository $prepo, \Swift_Mailer $mailer, CartService $cartService, TranslatorInterface $translator)
    {
        //getting the cart from the cartService
        $items = $cartService->getFullCart();
        //initiating the arrays
        $products = [];
        $quantity = [];
        $translated = $translator->trans('En attente de confirmation');
        $translate = $translator->trans('Nouvelle commande');
        //getting the user infos as contact
        $contact = $this->getUser();
        //creating a new time variable
        $time = new \DateTime();
        //creating a new order variable
        $order = new Order; 
               //getting the instance of the entity manager and 
               $entityManager = $this->getDoctrine()->getManager();
               //tells the entity manager to manage the product
        //for every items in the cart do : 
        foreach($items as $key => $item){
                //getting the product 
                $product = $item["product"];
                //pushing the product in the products array
                array_push($products, $product);
                //pushing
                array_push($quantity , $item["quantity"]);
                //getting the product id 
                $id = $product->getId();
                //setting the user as the order's customer
                $order->setIdUser($contact) ; 
                //setting the quantity array in order
                $order->setQuantity($quantity);    
                //adding the product in the order
                $order->addHasProduct($product) ; 
                //this product is in the order
                $product->addInOrder($order);
                //setting the time of the order
                $order->setDateOfOrder($time);
                //setting the basic status of the order
                $order->setStatus($translated);
                //telling the entity manager to manage the order
                $entityManager->persist($order);
                //telling the entity manager to manage the product
                $entityManager->persist($product);
            }
        //updating the product and the order in the database
        $entityManager->flush();
        //emptying the cart
        $cartService->removeCart();
        //creating a new message with the following subject
        $message = (new \Swift_Message($translate))
        //getting the author's email
        ->setFrom($contact->getEmail())
        //sending to specific mail
        ->setTo('commande@solidarity-bond.fr')
        //sending reply to author's email
        ->setReplyTo($contact->getEmail())
        //setting the content of the mail with the selected template
        ->setBody($this->renderView('emails/emails_order.html.twig',[
            //setting the mail's contact info with contact variable
            'contact' => $contact,
            'products' => $products,
            'quantity' => $quantity,
        ]), 'text/html');
        //sending the message with the mailer
        $mailer->send($message);
        //redirecting to homepage
        return $this->redirectToRoute("confirmed_order", [
            'id' => $order->getId(),
        ]);
    }
    /**
     * @Route("/confirmed_{id}", name="confirmed_order")
     */
    public function confirmed_order($id, OrderRepository $orepo, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //geting the specific order with the given id
        $order = $orepo->find($id);
        //redering the order detail page
        return $this->render('shop/order/confirm.html.twig', [
            'order' => $order,
            'num' => $num
        ]);
    }

    /**
     * @Route("/order_{id}", name="order_detail")
     */
    public function order_detail($id, OrderRepository $orepo, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //geting the specific order with the given id
        $order = $orepo->find($id);
        //redering the order detail page
        return $this->render('shop/order/orderPage.html.twig', [
            'order' => $order,
            'num' => $num
        ]);
    }

}
