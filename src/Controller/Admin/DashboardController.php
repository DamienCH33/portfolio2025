<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('admin/dashboard.html.twig', [
            'contacts' => $contacts,
            'visits_month' => 0,
            'contacts_month' => count($contacts),
            'conversion' => 0,
            'visits_today' => 0,
            'months_labels' => [],
            'monthly_visits' => [],
            'monthly_contacts' => []
        ]);
    }

    #[Route('/contacts/delete/{id}', name: 'admin_contact_delete', methods: ['POST'])]
    public function deleteContact(Request $request, EntityManagerInterface $em, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete_contact_' . $contact->getId(), $request->request->get('_token'))) {
            $em->remove($contact);
            $em->flush();
            $this->addFlash('success', 'Contact supprimé');
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}
