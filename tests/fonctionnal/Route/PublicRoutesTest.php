<?php

namespace App\Tests\Route;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicRoutesTest extends WebTestCase
{
    public function testPublicPages(): void
    {
        $client = static::createClient();

        $routes = [
            '/',
            '/contact',
        ];

        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseStatusCodeSame(200);
        }
    }
}
