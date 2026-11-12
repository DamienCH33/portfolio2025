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
            '/services',
            '/portfolio',
            '/about',
        ];

        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseStatusCodeSame(200);
        }
    }
}
