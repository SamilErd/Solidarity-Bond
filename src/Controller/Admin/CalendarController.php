<?php

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MachineRepository;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends AbstractController
{
    /**
     * @Route("/admin/calendar", name="admin_calendar")
     */
    public function Calendar(CartService $cartService)
	{
		$num = $cartService->getCartItemNum();
		return $this->render('admin/calendar.html.twig', ['num' => $num]);
	}
}
