<?php

namespace App\Tests\Controller\AdminControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminDashboardTest extends WebTestCase
{
    public function testDashboardRedirectsIfNotLogged(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        $this->assertResponseRedirects();
    }
}
