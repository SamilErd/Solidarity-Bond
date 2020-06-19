<?php

namespace App\Controller\Shop;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\Order;
use App\Service\Cart\CartService;



class ShopController extends AbstractController
{



    /**
     * @Route("/products", name="show_products")
     */
    public function show_products(ProductRepository $prepo)
    {
        //Getting all products from database
        $products = $prepo->findAll();
        //rendering the product page
        return $this->render('shop/products.html.twig', [
            //giving all the products of the database to the page
            "products" => $products,
        ]);
    }

    

    /**
     * @Route("/product_{id}", name="show_product")
     */
    public function show_product($id, ProductRepository $prepo)
    {
        $product = $prepo->find($id);
        //rendering the specific product's page [WHICH HASNT BEEN MADE FOR THE MOMENT]



        return $this->render('shop/product.html.twig', [
            "product" => $product,
        ]);
    }
    /**
     * @Route("/show_order", name="show_order")
     */
    public function show_order(CartService $cartService)
    {
        
        //rendering the specific product's page 

        $itemsJson = json_encode($cartService->getFullCart());

        return $this->render('shop/order.html.twig', [
            'items' => $cartService->getFullCart(),
            'itemsJson' => $itemsJson,
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/order_product", name="order_product")
     */
    public function order_product( ProductRepository $prepo, \Swift_Mailer $mailer, CartService $cartService)
    {
        $items = $cartService->getFullCart();
        $products = [];
        $quantity = [];
        $contact = $this->getUser();
        $time = new \DateTime();
        
        $order = new Order; 
               //getting the instance of the entity manager and 
               $entityManager = $this->getDoctrine()->getManager();
               //tells the entity manager to manage the product
        
        foreach($items as $key => $item){
            
                $product = $item["product"];
                array_push($products, $product);
                array_push($quantity , $item["quantity"]);
                $id = $product->getId();
                $order->setIdUser($contact) ; 
                $order->setQuantity($quantity);    
                $order->addHasProduct($product) ; 
                $product->addInOrder($order);
                $order->setDateOfOrder($time);
                $order->setStatus("En attente de confirmation");
 
                
                $entityManager->persist($order);
                $entityManager->persist($product);
                
            }


                //inserting the product in the database
                $entityManager->flush();
                
        $cartService->removeCart();


        $message = (new \Swift_Message('Nouveau Message'))
        //getting the author's email
        ->setFrom($contact->getEmail())
        //sending to specific mail
        ->setTo('contact@solidaritybond-stras.yj.fr')
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



        return $this->redirectToRoute("index");

        
    }
        
}
