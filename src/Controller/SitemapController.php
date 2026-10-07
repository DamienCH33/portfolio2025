<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapController extends AbstractController
{
    /**
     * Plan du site pour les moteurs de recherche (référencé dans public/robots.txt).
     * Les pages projet détaillées y sont ajoutées automatiquement.
     */
    #[Route('/sitemap.xml', name: 'app_sitemap', methods: ['GET'], format: 'xml')]
    public function index(EntityManagerInterface $em): Response
    {
        $urls = [];
        foreach ([
            'app_home' => '1.0',
            'app_services' => '0.9',
            'app_portfolio' => '0.8',
            'app_about' => '0.7',
            'app_contact' => '0.6',
            'app_legal' => '0.2',
        ] as $route => $priority) {
            $urls[] = ['loc' => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => $priority, 'lastmod' => null];
        }

        foreach ($em->getRepository(Project::class)->findBy([], ['createdAt' => 'DESC']) as $project) {
            if ($project->hasCaseStudy()) {
                $urls[] = [
                    'loc' => $this->generateUrl('app_project_show', ['slug' => $project->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'priority' => '0.8',
                    'lastmod' => $project->getCreatedAt()?->format('Y-m-d'),
                ];
            }
        }

        $response = $this->render('sitemap.xml.twig', ['urls' => $urls]);
        $response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

        return $response;
    }
}
