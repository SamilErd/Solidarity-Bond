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
            $stock = $product->getStock();
            $newStock = $stock + $_POST['SI'];
            $product->setStock($newStock);
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


       
       if(isset($_FILES["II"])){

            $ext_error = false;
            $extensions = array('jpg','jpeg','png','JPG','JPEG','PNG');
            $fileExtension = explode(".", $_FILES["II"]["name"])[1];
            if(!in_array($fileExtension, $extensions)){
                $ext_error = true;
            } else {
                $fileName = md5(uniqid()).'.'.$fileExtension;
                move_uploaded_file($_FILES["II"]["tmp_name"], 'images/products/'.$fileName);
                unlink('images/products/'.$product->getImage());
                $product->setImage($fileName);
                $entityManager->persist($product);
            //basically updating the user infos in the database
            $entityManager->flush();

            }
            //Setting a new unique name for the image
            
            
            
                
            
        }





        //rendering the specific product's page
        return $this->render('shop/product/product.html.twig', [
            "product" => $product,
        ]);
    }
}
