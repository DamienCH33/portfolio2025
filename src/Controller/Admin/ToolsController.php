<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/outils')]
#[IsGranted('ROLE_ADMIN')]
class ToolsController extends AbstractController
{
    /**
     * Affichette A5 « Laissez-nous un avis » avec QR code, à enregistrer en PDF
     * depuis le navigateur puis à envoyer au client (offre Avis Google).
     */
    #[Route('/qr-avis', name: 'admin_tools_qr', methods: ['GET'])]
    public function qrReview(): Response
    {
        return $this->render('admin/tools/qr_review.html.twig');
    }
}
