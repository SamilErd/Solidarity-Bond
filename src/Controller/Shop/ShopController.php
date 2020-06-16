<?php

namespace App\Controller\Shop;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\Order;



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
     * @Route("/show_order_{id}", name="show_order")
     */
    public function show_order($id, ProductRepository $prepo)
    {
        $product = $prepo->find($id);
        //rendering the specific product's page 



        return $this->render('shop/order.html.twig', [
            "product" => $product,
        ]);
    }

    /**
     * @Route("/order_product_{id}_{quantity}", name="order_product")
     */
    public function order_product($id,$quantity, ProductRepository $prepo, \Swift_Mailer $mailer)
    {


        $product = $prepo->find($id);
        $contact = $this->getUser();
        $time = new \DateTime();
        $order = new Order; 

        //rendering the specific product's page 
        $this->addFlash('succes', 'Votre commande a été passée avec succès.');
        //
        $message = (new \Swift_Message('Nouveau Message'))
        //getting the author's email
        ->setFrom($contact->getEmail())
        //sending to specific mail
        ->setTo('contact@solidaritybond-stras.yj.fr')
        //sending reply to author's email
        ->setReplyTo($contact->getEmail())
        //setting the content of the mail with the selected template
        ->setBody($this->renderView('shop/emails_contact.html.twig',[
            //setting the mail's contact info with contact variable
            'contact' => $contact,
            'product' => $product,
            'Quantity' => $quantity,
        ]), 'text/html');
        //sending the message with the mailer
        $mailer->send($message);
        //redirecting to homepage

        $order->setIdUser($contact) ;  

        $order->addIdProduct($product) ; 
        
        $order->setQuantity($quantity);
        
        $order->setDateOfOrder($time);

        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to manage the product
        $entityManager->persist($order);
        //inserting the product in the database
        $entityManager->flush();
        return $this->render('shop/confirm.html.twig', [
            "product" => $product,
            "quantity" => $quantity
        ]);
            
        
    }
        

/*
        

*/
        


        }