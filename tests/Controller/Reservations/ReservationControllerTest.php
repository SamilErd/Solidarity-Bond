<?php
namespace App\Tests\Controlle\Reservations;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testGoogleAuthentication()
    {
        $client = static::createClient();

        $client->request('GET', '/reservation/monday/morning0');
	$this->assertResponseIsSuccessful();
	$this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
