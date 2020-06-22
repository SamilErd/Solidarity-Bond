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
     * @Route("/shop", name="show_products")
     */
    public function show_products(ProductRepository $prepo)
    {
        //Getting all products from database
        $products = $prepo->findAll();
        //rendering the product page
        return $this->render('shop/product/products.html.twig', [
            //giving all the products of the database to the page
            "products" => $products,
        ]);
    }

    

    /**
     * @Route("/shop/product_{id}", name="show_product")
     */
    public function show_product($id, ProductRepository $prepo)
    {

        
        //finding the specific product in the database
        $product = $prepo->find($id);
        
        //Getting an instance of the entity manager
        $entityManager = $this->getDoctrine()->getManager();
        if(!empty($_POST["NI"])){
            $product->setProductName($_POST['NI']);
            $entityManager->persist($product);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }
        if(!empty($_POST["PI"])){
            $product->setPrice($_POST['PI']);
            $entityManager->persist($product);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }
        if(!empty($_POST["SI"])){
            $product->setStock($_POST['SI']);
            $entityManager->persist($product);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }
        if(!empty($_POST["DI"])){
            $product->setDescription($_POST['DI']);
            $entityManager->persist($product);
            //basically updating the user infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }

        
         /*   if(!empty($_POST["DI"])){
                $target_dir = "uploads/";
                $file = $target_dir . basename($_FILES["II"]["name"]);
                
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                $fileName = md5(uniqid()).'.'.$imageFileType;
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    dd( "File is an image - " . $check["mime"] . ".");
                    $uploadOk = 1;
                } else {
                    dd("File is not an image.");
                    $uploadOk = 0;
                }
            }
        }*/





        //rendering the specific product's page
        return $this->render('shop/product/product.html.twig', [
            "product" => $product,
        ]);
    }
}
