<?php

namespace App\Tests\Controller\AdminControllerTest;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminAccessTest extends WebTestCase
{
    public function testAdminCanAccessDashboard(): void
    {
        $client = static::createClient();

        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $user = $em->getRepository(User::class)->findOneBy([
            'email' => 'admin@test.com',
        ]);

        if (!$user) {
            $user = new User();
            $user->setEmail('admin@test.com');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setCreatedAt(new \DateTimeImmutable());

            $hashedPassword = $passwordHasher->hashPassword($user, 'test');
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
        }

        $client->loginUser($user);

        $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
    }
}
