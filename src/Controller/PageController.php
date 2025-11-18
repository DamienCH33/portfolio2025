<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/skills', name: 'app_skills', methods: ['GET'])]
    public function skills(): Response
    {
        return $this->render('skills.html.twig');
    }

    #[Route('/portfolio', name: 'app_portfolio', methods: ['GET'])]
    public function porftolio(): Response
    {
        return $this->render('portfolio.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}
