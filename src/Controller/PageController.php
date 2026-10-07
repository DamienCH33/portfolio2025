<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Skill;
use App\Form\ContactType;
use App\Repository\OfferRepository;
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
        $skills = $em->getRepository(Skill::class)->findBy([], ['priority' => 'ASC', 'name' => 'ASC']);
        $educations = $em->getRepository(\App\Entity\Education::class)->findAll();
        $projects = $em->getRepository(\App\Entity\Project::class)->findBy([], ['createdAt' => 'DESC'], 4);
        $profile = $em->getRepository(\App\Entity\Profile::class)->getSingleton();
        // Projets avec un lien « en ligne » : panneau du hero
        $liveProjects = $em->getRepository(\App\Entity\Project::class)->createQueryBuilder('p')
            ->where('p.demoUrl IS NOT NULL')
            ->andWhere("p.demoUrl <> ''")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        $form = $this->createForm(ContactType::class, new Contact(), [
            'action' => '#contact',
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('index.html.twig', [
                'skills' => $skills,
                'educations' => $educations,
                'projects' => $projects,
                'live_projects' => $liveProjects,
                'profile' => $profile,
                'form' => $form->createView(),
            ]);
        }

        return $this->forward('App\Controller\ContactController::contactForm', [
            'request' => $request,
            'em' => $em,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/portfolio', name: 'app_portfolio', methods: ['GET'])]
    public function porftolio(EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(\App\Entity\Project::class)->findBy([], ['createdAt' => 'DESC']);
        return $this->render('portfolio.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/services', name: 'app_services', methods: ['GET'])]
    public function services(OfferRepository $offers): Response
    {
        return $this->render('services.html.twig', [
            'offers' => $offers->findPublished(),
        ]);
    }

    #[Route('/mentions-legales', name: 'app_legal', methods: ['GET'])]
    public function legal(): Response
    {
        return $this->render('legal.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('contact.html.twig',);
    }
}
