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
        if(isset($_POST["NI"])){
            //setting a new name for the product
            $product->setProductName($_POST['NI']);
            //telling the entity manager to manage the product
            $entityManager->persist($product);
            //basically updating the product infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }
        if(isset($_POST["PI"])){
            //setting a new price for the product
            $product->setPrice($_POST['PI']);
            //telling the entity manager to manage the product
            $entityManager->persist($product);
            //basically updating the product infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }
        if(isset($_POST["SI"])){
            //setting a new stock for the product
            $stock = $product->getStock();
            $newStock = $stock + $_POST['SI'];
            $product->setStock($newStock);
            //telling the entity manager to manage the product
            $entityManager->persist($product);
            //basically updating the product infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }
        if(isset($_POST["DI"])){
            //setting a new description for the product
            $product->setDescription($_POST['DI']);
            //telling the entity manager to manage the product
            $entityManager->persist($product);
            //basically updating the product infos in the database
            $entityManager->flush();
            return $this->redirectToRoute('show_product', ['id' => $id]);
        }


       
       if(isset($_FILES["II"])){
            //setting the error to false by default
            $ext_error = false;
            //setting a list of extensions authorized
            $extensions = array('jpg','jpeg','png','JPG','JPEG','PNG');
            //getting the file's extension
            $fileExtension = explode(".", $_FILES["II"]["name"])[1];
            //if the file's extension is not accepted
            if(!in_array($fileExtension, $extensions)){
                $error = true;
                //rendering the specific product's page
                return $this->render('shop/product/product.html.twig', [
                    "product" => $product,
                    "error" => $error
                ]);
            } else {
                //Setting a new unique name for the image
                $fileName = md5(uniqid()).'.'.$fileExtension;
                //moving the file to the products directory
                move_uploaded_file($_FILES["II"]["tmp_name"], 'images/products/'.$fileName);
                //deleting the old picture
                unlink('images/products/'.$product->getImage());
                //setting the new one in the database
                $product->setImage($fileName);
                //telling the entity manager to manage the product
                $entityManager->persist($product);
                //basically updating the product infos in the database
                $entityManager->flush();
            }
        }





        //rendering the specific product's page
        return $this->render('shop/product/product.html.twig', [
            "product" => $product,
            
        ]);
    }
}
