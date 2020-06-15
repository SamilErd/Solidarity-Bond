<?php

namespace App\Controller\Shop;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Entity\Product;



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
     * @Route("/payment_{id}", name="payment")
     */
    public function payment(Request $request,$id, ProductRepository $prepo)
    {
        //rendering the specific product's page [WHICH HASNT BEEN MADE FOR THE MOMENT]
        $id = $request->request->get('id');
        $product = $prepo->find($id);
        \Stripe\Stripe::setApiKey("sk_test_51GtxmVIdMDT276mzSGsxQhPhaEyMYKgvIJS2OBBw0QGMibDMSKKnMnwZq17BMbOxqbwSxpiYSnHQHCjOSguSSYAk00R2E3RFsj");
       
        \Stripe\Charge::create(array(
            "amount" => $product->getPrice(),
            "currency" => "eur",
            "source" => $request->request->get('stripeToken'),
            "description" => "Paiement de".$this->getUser()->getEmail()
        ));


        return $this->render('shop/product.html.twig', [
            "product" => $product,
        ]);
    }



}