<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\Education;
use App\Entity\Contact;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $contacts = $em->getRepository(Contact::class)->findAll();
        return $this->render('admin/dashboard.html.twig',[
            'contacts' => $contacts,
        ]);
    }   
    
}
