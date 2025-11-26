<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $contacts = $em->getRepository(Contact::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
