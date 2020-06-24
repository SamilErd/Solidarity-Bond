<?php

namespace App\Controller\Admin;


use App\Form\NewProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Entity\Product;



class ProductController extends AbstractController
{



    /**
     * @Route("/admin_products", name="admin_products")
     */
    public function admin_products(ProductRepository $prepo)
    {
        //Getting all products from database
        $products = $prepo->findAll();
        //rendering the product page
        return $this->render('Shop/product/products.html.twig', [
            //giving all the products of the database to the page
            "products" => $products,
        ]);
    }

    /**
     * @Route("/delete_product_{id}", name="delete_product")
     */
    public function delete_product($id, ProductRepository $prepo)
    {
        //Getting the product from the database
        $product = $prepo->find($id);
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to remove the product
        $entityManager->remove($product);
        //updating the database
        $entityManager->flush();
        
        //rendirecting to products
        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin_new_product", name="admin_new_product")
     */
    public function admin_new_product(Request $request)
    {
        //creating an instance of the product entity
        $product = new Product() ;
        //creating a form with the product instance and the NewProductType template
        $formnp = $this->createForm(NewProductType::class, $product);
        //handles the answer from the new product's form
        $formnp->handleRequest($request);

        //if the form is submitted without errors
        if ($formnp->isSubmitted() && $formnp->isValid()) {

            //The file is taken for thhe image
            $file = $formnp['Image']->getData();
            //Setting a new unique name for the image
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            //moving the image in a specific folder set in config/services.yml
            $file->move($this->getParameter('upload_directory_photos'), $fileName);
            //setting the image field of the product with the filename
            $product->setImage($fileName);
            //getting the instance of the entity manager and 
            $entityManager = $this->getDoctrine()->getManager();
            //tells the entity manager to manage the product
            $entityManager->persist($product);
            //inserting the product in the database
            $entityManager->flush();
            
            //redirecting to the products page
            return $this->redirectToRoute('admin_products');
        }
        //rendering the product creating page
        return $this->render('admin/products/new_product.html.twig', [
            //giving this page the form value
            'formnp' => $formnp->createView()
        ]);
    }


    /**
     * @Route("/admin_more", name="admin_more")
     */
    public function admin_more(ProductRepository $prepo)
    {
        //getting the selected value for the product's stock
        $morenum = $_POST['morenum'];
        //selecting the specific product
        $product = $prepo->find($_POST['id']);
        //if the selected value is a number(integer)
        if(is_numeric($morenum)){
            //getting the actual product's stock
            $actualStock = $product->getStock();
            //calculating the new stock status
            $finalStock = $actualStock + $morenum;
            //setting the stock with the new stock status
            $product->setStock($finalStock);
            //getting the instance of the entity manager and 
            $entityManager = $this->getDoctrine()->getManager();
            //tells the entity manager to manage the product
            $entityManager->persist($product);
            //updating the product in the database
            $entityManager->flush();
        }
        //redirecting to the products page
        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/order_{id}_sent", name="order_sent")
     */
    public function order_sent($id, OrderRepository $orepo)
    {
        //selecting the specific id's order
        $order = $orepo->find($id);
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($order->getHasProduct() as $key => $product){
            $quantity = $order->getQuantity()[$key];
            $product->setStock($product->getStock() - $quantity);
            //tells the entity manager to manage the product
            $entityManager->persist($product);
            //updating the product in the database
            $entityManager->flush();
        }

        //setting the new order status
        $order->setStatus("Expédié");
        //tells the entity manager to manage the product
        $entityManager->persist($order);
        //updating the product in the database
        $entityManager->flush();
        //rendering the update product's stock page
        return $this->redirectToRoute('order_detail', [
            //giving the page the product variable
            "id" => $id,
        ]);
    }
    /**
     * @Route("/order_{id}_prepare", name="order_prepare")
     */
    public function order_prepare($id, OrderRepository $orepo)
    {

        //selecting the specific id's order
        $order = $orepo->find($id);
        //setting the new order status
        $order->setStatus("En préparation");
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to manage the product
        $entityManager->persist($order);
        //updating the product in the database
        $entityManager->flush();
        //rendering the update product's stock page
        return $this->redirectToRoute('order_detail', [
            //giving the page the product variable
            "id" => $id,
        ]);
    }

    /**
     * @Route("/admin_sold", name="admin_sold")
     */
    public function admin_sold(ProductRepository $prepo)
    {
        //getting the selected value for the product's stock
        $soldnum = $_POST['soldnum'];
        //selecting the specific product
        $product = $prepo->find($_POST['id']);
        //if the selected value is a number(integer)
        if(is_numeric($soldnum)){
            //getting the actual product's stock
            $actualStock = $product->getStock();
            //calculating the new stock status
            $finalStock = $actualStock - $soldnum;
            //setting the stock with the new stock status
            $product->setStock($finalStock);
            //getting the instance of the entity manager and 
            $entityManager = $this->getDoctrine()->getManager();
            //tells the entity manager to manage the product
            $entityManager->persist($product);
            //updating the product in the database
            $entityManager->flush();
        }
        //redirecting to the products page
        return $this->redirectToRoute('admin_products');
    }


    /**
     * @Route("/admin_product_{id}", name="admin_product")
     */
    public function admin_product($id)
    {
        //rendering the specific product's page [WHICH HASNT BEEN MADE FOR THE MOMENT]
        return $this->render('admin/product.html.twig');
    }
}