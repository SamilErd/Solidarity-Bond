<?php
namespace App\Tests\Controller\Reservations;

use App\Controller\Reservations\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{
    public function testClient()
    {
        $client = new Client();
        $result = $client->getClient();
        $this->assertNotNull($result);
    }
}
