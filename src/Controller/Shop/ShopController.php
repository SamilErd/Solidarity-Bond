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
        //finding the specific product in the database
        $product = $prepo->find($id);
        //rendering the specific product's page
        return $this->render('shop/product.html.twig', [
            "product" => $product,
        ]);
    }
    /**
     * @Route("/show_order", name="show_order")
     */
    public function show_order(CartService $cartService)
    {
        //redering the order panel with the users cart
        return $this->render('shop/order.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/order_product", name="order_product")
     */
    public function order_product( ProductRepository $prepo, \Swift_Mailer $mailer, CartService $cartService)
    {
        //getting the cart from the cartService
        $items = $cartService->getFullCart();
        //initiating the arrays
        $products = [];
        $quantity = [];
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
                $order->setStatus("En attente de confirmation");
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
        $message = (new \Swift_Message('Nouvelle commande'))
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
