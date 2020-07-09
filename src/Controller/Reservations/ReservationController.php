<?php

namespace App\Controller\Reservations;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MachineRepository;
use App\Service\Cart\CartService;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Reservations\Client;

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
	'num' => $num,		
	'machines' => $machines,
	]);
    }

    /**
     * @Route("/reservation/monday/morning{id}", name="monday_morning")
     */
    public function reserve_monday_morning(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-13T07:00:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-13T11:00:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/monday/afternoon{id}", name="monday_afternoon")
     */
    public function reserve_monday_afternoon(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-13T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-13T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/tuesday/morning{id}", name="tuesday_morning")
     */
    public function reserve_tuesday_morning(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-14T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-14T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/tuesday/afternoon{id}", name="tuesday_afternoon")
     */
    public function reserve_tuesday_afternoon(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-14T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-14T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/wednesday/morning{id}", name="wednesday_morning")
     */
    public function reserve_wednesday_morning(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-15T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-15T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/wednesday/afternoon{id}", name="wednesday_afternoon")
     */
    public function reserve_wednesday_afternoon(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-15T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-15T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/thursday/morning{id}", name="thursday_morning")
     */
    public function reserve_thursday_morning(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-16T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-16T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/thursday/afternoon{id}", name="thursday_afternoon")
     */
    public function reserve_thursday_afternoon(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-16T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-17T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/friday/morning{id}", name="friday_morning")
     */
    public function reserve_friday_morning(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-15T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-17T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
    }

    /**
     * @Route("/reservation/friday/afternoon{id}", name="friday_afternoon")
     */
    public function reserve_friday_afternoon(MachineRepository $mrepo, CartService $cartService, string $id)
    {
	$num = $cartService->getCartItemNum();
	$event = new Google_Service_Calendar_Event(array(
	  'summary' => $id,
	  'start' => array(
	    'dateTime' => '2020-07-17T11:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'end' => array(
	    'dateTime' => '2020-07-17T15:30:00Z',
	    'timeZone' => 'Europe/Paris',
	  ),
	  'attendees' => array(
	    array('email' => 'kingvic2000@gmail.com')
	  ),
	  'reminders' => array(
	    'useDefault' => FALSE,
	    'overrides' => array(
	      array('method' => 'email', 'minutes' => 24 * 60),
	      array('method' => 'popup', 'minutes' => 10),
	    ),
	  ),
	));
	$googleClient = new Client();
	$client = $googleClient->getClient();
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
	    return $this->redirect($authUrl);
	}
    }
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	return $this->render('reservation/reserved.html.twig', [
            'num' => $num
        ]);
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
