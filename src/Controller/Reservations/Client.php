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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Client extends AbstractController
{
/**
* @Route("/client", name="getClient")
*/

public function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Solidarity-Bond');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('/home/victor/Projects/Solidarity-Bond/credentials.json');
    $client->setRedirectUri('https://127.0.0.1:8000/client/validate');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    return $client;
}
/**
* @Route("/client/validate", name="validate")
*/
public function validate()
{
	
	$client = $this->getClient();
	$authCode = $_GET['code'];
	$tokenPath = '/home/victor/Projects/Solidarity-Bond/token.json';
	$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
	$accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
	if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
	file_put_contents($tokenPath, json_encode($client->getAccessToken()));
	return $this->redirect($this->generateUrl('reservation'));
}
}
?>
