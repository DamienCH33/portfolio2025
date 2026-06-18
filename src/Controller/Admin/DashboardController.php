<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $visitRepo = $em->getRepository(Visit::class);
        $contactRepo = $em->getRepository(Contact::class);

        $contacts = $contactRepo->findBy([], ['createdAt' => 'DESC']);

        $now = new \DateTimeImmutable();
        $startOfToday = $now->setTime(0, 0);
        $startOfMonth = $now->modify('first day of this month')->setTime(0, 0);

        // Compteurs
        $visitsToday   = $this->countSince($visitRepo, $startOfToday);
        $visitsMonth   = $this->countSince($visitRepo, $startOfMonth);
        $contactsMonth = $this->countSince($contactRepo, $startOfMonth);
        $conversion    = $visitsMonth > 0 ? round($contactsMonth / $visitsMonth * 100, 1) : 0;

        $frMonths = ['', 'Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        $labels = [];
        $monthlyVisits = [];
        $monthlyContacts = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = $startOfMonth->modify("-$i months");
            $end   = $start->modify('+1 month');

            $labels[]          = $frMonths[(int) $start->format('n')] . ' ' . $start->format('y');
            $monthlyVisits[]   = $this->countBetween($visitRepo, $start, $end);
            $monthlyContacts[] = $this->countBetween($contactRepo, $start, $end);
        }

        return $this->render('admin/dashboard.html.twig', [
            'contacts'         => $contacts,
            'visits_month'     => $visitsMonth,
            'contacts_month'   => $contactsMonth,
            'conversion'       => $conversion,
            'visits_today'     => $visitsToday,
            'months_labels'    => $labels,
            'monthly_visits'   => $monthlyVisits,
            'monthly_contacts' => $monthlyContacts,
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

    private function countSince(EntityRepository $repo, \DateTimeInterface $since): int
    {
        return (int) $repo->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.createdAt >= :since')
            ->setParameter('since', $since)
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function countBetween(EntityRepository $repo, \DateTimeInterface $start, \DateTimeInterface $end): int
    {
        return (int) $repo->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.createdAt >= :start')
            ->andWhere('e.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
