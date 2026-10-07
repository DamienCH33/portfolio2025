<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/services')]
#[IsGranted('ROLE_ADMIN')]
class OffersController extends AbstractController
{
    #[Route('', name: 'admin_offers', methods: ['GET'])]
    public function index(OfferRepository $offers): Response
    {
        return $this->render('admin/offers/list.html.twig', [
            'offers' => $offers->findAllOrdered(),
        ]);
    }

    #[Route('/ajouter', name: 'admin_offers_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, OfferRepository $offers): Response
    {
        $offer = (new Offer())->setPosition((count($offers->findAll()) + 1) * 10);
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offer);
            $em->flush();
            $this->addFlash('success', sprintf('Offre « %s » ajoutée.', $offer->getTitle()));

            return $this->redirectToRoute('admin_offers');
        }

        return $this->render('admin/offers/form.html.twig', [
            'form' => $form,
            'offer' => null,
        ]);
    }

    #[Route('/{id}/modifier', name: 'admin_offers_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $em, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', sprintf('Offre « %s » enregistrée.', $offer->getTitle()));

            return $this->redirectToRoute('admin_offers');
        }

        return $this->render('admin/offers/form.html.twig', [
            'form' => $form,
            'offer' => $offer,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'admin_offers_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, EntityManagerInterface $em, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete_offer_'.$offer->getId(), (string) $request->request->get('_token'))) {
            $em->remove($offer);
            $em->flush();
            $this->addFlash('success', 'Offre supprimée.');
        }

        return $this->redirectToRoute('admin_offers');
    }
}
