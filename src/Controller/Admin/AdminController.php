<?php

namespace App\Controller\Admin;


use App\Form\NewProductType;
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
        //rendering the admin page for the administrator
        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/admin_orders", name="admin_orders")
     */
    public function admin_orders()
    {
        //rendering the order management page for the administrator
        return $this->render('admin/orders.html.twig');
    }
}
