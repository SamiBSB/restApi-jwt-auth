<?php

namespace App\tests\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase{

public function testLogin(){

    $client = static::createClient();

    $client->request('GET', '/login');

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
}


}