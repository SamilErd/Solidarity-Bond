<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MachineRepository;
use App\Service\Cart\CartService;
use App\Entity\Machine;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function show_machines(MachineRepository $mrepo, CartService $cartService)
    {
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //Getting all machines from database
        $machines = $mrepo->findAll();
        //rendering the reservation page
        return $this->render('reservation/index.html.twig', [
            //giving all the machines of the database to the page
            "machines" => $machines,
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/machine", name="machine")
     */
    public function reserve_machine(MachineRepository $mrepo)
    {
        $client = new \Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
        // offline access will give you both an access and refresh token so that
        // your app can refresh the access token without user interaction.
        $client->setAccessType('offline');
        // Using "consent" ensures that your application always receives a refresh token.
        $client->setApprovalPrompt("consent");
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $calendarList = $client->calendarList->listCalendarList();
        $machines = $mrepo->findAll();
        $calendar;
        $event = new Google_Service_Calendar_Event(array(
            'summary' => 'Google I/O 2015',
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
            'dateTime' => '2015-05-28T09:00:00-07:00',
            'timeZone' => 'America/Los_Angeles',
        ),
        'end' => array(
            'dateTime' => '2015-05-28T17:00:00-07:00',
            'timeZone' => 'America/Los_Angeles',
        ),
        'recurrence' => array(
            'RRULE:FREQ=DAILY;COUNT=2'
        ),
          'attendees' => array(
            array('email' => 'lpage@example.com'),
            array('email' => 'sbrin@example.com'),
        ),
          'reminders' => array(
            'useDefault' => FALSE,
            'overrides' => array(
                array('method' => 'email', 'minutes' => 24 * 60),
                array('method' => 'popup', 'minutes' => 10),
           ),
        ),
        ));

        $event = $client->events->insert($calendar.id, $event);
    }

    /**
     * @Route("/reservation/move{delay}", name="move")
     */
    public function move_reservations(int $delay, MachineRepository $mrepo)
    {
        $client = new \Google_Client();
        $client->setAuthConfig($this->getParameter('project_directory').'/client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
        // offline access will give you both an access and refresh token so that
        // your app can refresh the access token without user interaction.
        $client->setAccessType('offline');
        // Using "consent" ensures that your application always receives a refresh token.
        $client->setApprovalPrompt("consent");
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $calendarList = $client->calendarList->listCalendarList();
        $machines = $mrepo->findAll();
        $calendar;
	$events = $service->events->listEvents($calendar.id);
        $event = $client->events->patch($calendar.id, $event);
    }
    /**
     * @Route("/reservation/machine/add", name="add_machine")
     */
    public function add_machine(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $token = new CsrfToken('machine', $_POST['_csrf_token']);

        if (!$csrfTokenManager->isTokenValid($token)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }
        $machine = new Machine();
        $machine->setName($_POST['machine']);
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to manage the product
        $entityManager->persist($machine);
        //updating the product in the database
        $entityManager->flush();
        
        return $this->redirectToRoute('reservation');
    }



}
