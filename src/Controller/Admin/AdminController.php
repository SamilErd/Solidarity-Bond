<?php

namespace App\Controller\Admin;


use App\Form\NewProductType;
use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\ProductRepository;



class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/admin_orders", name="admin_orders")
     */
    public function admin_orders()
    {
        return $this->render('admin/orders.html.twig');
    }

    /**
     * @Route("/admin_products", name="admin_products")
     */
    public function admin_products(ProductRepository $prepo)
    {
        $products = $prepo->findAll();

        return $this->render('admin/products.html.twig', [
            "products" => $products,
        ]);
    }

    /**
     * @Route("/admin_new_product", name="admin_new_product")
     */
    public function admin_new_product(Request $request)
    {
        $product = new Product() ;
        $formnp = $this->createForm(NewProductType::class, $product);
        $formnp->handleRequest($request);

        if ($formnp->isSubmitted() && $formnp->isValid()) {
            $directory="public/images/products";

            $file = $formnp['Image']->getData();
            $filename = $file->getClientOriginalName();
            $file->move($directory, $filename);
            
            $product->setImage($filename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('admin_products');
        }
        return $this->render('admin/products/new_product.html.twig', [
            'formnp' => $formnp->createView()
        ]);
    }


    /**
     * @Route("/admin_more_page_{id}", name="admin_more_page")
     */
    public function admin_more_page($id, ProductRepository $prepo)
    {
        $product = $prepo->find($id);

        
        return $this->render('admin/products/more_product.html.twig', [
            "product" => $product,
        ]);
    }

    /**
     * @Route("/admin_more", name="admin_more")
     */
    public function admin_more(ProductRepository $prepo)
    {

        $morenum = $_POST['morenum'];
        $product = $prepo->find($_POST['id']);
        if(is_numeric($morenum)){
            $actualStock = $product->getStock();
            $finalStock = $actualStock + $morenum;
            $product->setStock($finalStock);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }


        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin_sold_page_{id}", name="admin_sold_page")
     */
    public function admin_sold_page($id, ProductRepository $prepo)
    {
        $product = $prepo->find($id);
        return $this->render('admin/products/sold_product.html.twig', [
            "product" => $product,
        ]);
    }
    /**
     * @Route("/admin_sold", name="admin_sold")
     */
    public function admin_sold(ProductRepository $prepo)
    {

        $soldnum = $_POST['soldnum'];
        $product = $prepo->find($_POST['id']);
        if(is_numeric($soldnum)){

            $actualStock = $product->getStock();
            $finalStock = $actualStock - $soldnum;
            $product->setStock($finalStock);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

        }


        return $this->redirectToRoute('admin_products');
    }


    /**
     * @Route("/admin_product_", name="admin_product")
     */
    public function admin_product()
    {
        return $this->render('admin/product.html.twig');
    }


}
