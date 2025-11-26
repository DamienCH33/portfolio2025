<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Skill;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $skills = $em->getRepository(Skill::class)->findAll();
        $educations = $em->getRepository(\App\Entity\Education::class)->findAll();
        $projects = $em->getRepository(\App\Entity\Project::class)->findBy([], ['createdAt' => 'DESC'], 3);

        $form = $this->createForm(ContactType::class, new Contact(), [
            'action' => '#contact',
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('index.html.twig', [
                'skills' => $skills,
                'educations' => $educations,
                'projects' => $projects,
                'form' => $form->createView(),
            ]);
        }

        return $this->forward('App\Controller\ContactController::contactForm', [
            'request' => $request,
            'em' => $em,
        ]);
    }

    #[Route('/skills', name: 'app_skills', methods: ['GET'])]
    public function skills(EntityManagerInterface $em): Response
    {
        $skills = $em->getRepository(Skill::class)->findAll();

        return $this->render('skills.html.twig', [
            'skills' => $skills,
        ]);
    }

    #[Route('/portfolio', name: 'app_portfolio', methods: ['GET'])]
    public function porftolio(EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(\App\Entity\Project::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('portfolio.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}
