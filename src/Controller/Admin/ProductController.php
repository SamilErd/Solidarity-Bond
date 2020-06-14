<?php

namespace App\Controller\Admin;


use App\Form\NewProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
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
        return $this->render('admin/products.html.twig', [
            //giving all the products of the database to the page
            "products" => $products,
        ]);
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
     * @Route("/admin_more_page_{id}", name="admin_more_page")
     */
    public function admin_more_page($id, ProductRepository $prepo)
    {
        //getting the specific id's product
        $product = $prepo->find($id);
        //rendering the product more stock page
        return $this->render('admin/products/more_product.html.twig', [
            //giving the page the product variable
            "product" => $product,
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
     * @Route("/admin_sold_page_{id}", name="admin_sold_page")
     */
    public function admin_sold_page($id, ProductRepository $prepo)
    {
        //selecting the specific id's product
        $product = $prepo->find($id);
        //rendering the update product's stock page
        return $this->render('admin/products/sold_product.html.twig', [
            //giving the page the product variable
            "product" => $product,
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