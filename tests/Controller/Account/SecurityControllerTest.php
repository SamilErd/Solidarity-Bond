<?php

namespace App\tests\Controller\Account;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{

    public function testAdminUserAccess()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // get the test user
        $testUser = $userRepository->findOneByEmail('fablab@cesi.fr');

        // test $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }
    public function testAdminUserAccessCalendar()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // get the test user
        $testUser = $userRepository->findOneByEmail('fablab@cesi.fr');

        // test $testUser being logged in
        $client->loginUser($testUser);

	$client->request('GET', '/admin/calendar');
	$this->assertResponseIsSuccessful();
    }
    public function testAdminUserAccessUsers()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // get the test user
        $testUser = $userRepository->findOneByEmail('fablab@cesi.fr');

        // test $testUser being logged in
        $client->loginUser($testUser);

	$client->request('GET', '/admin_users');
	$this->assertResponseIsSuccessful();
    }
    public function testAdminUserAccessOrders()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // get the test user
        $testUser = $userRepository->findOneByEmail('fablab@cesi.fr');

        // test $testUser being logged in
        $client->loginUser($testUser);

	$client->request('GET', '/admin_users');
	$this->assertResponseIsSuccessful();
    }
}
