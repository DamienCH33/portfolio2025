<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact_form', methods: ['GET', 'POST'])]
    public function contactForm(
        Request $request,
        EntityManagerInterface $em,
        RateLimiterFactory $contactFormLimiter
    ): Response {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $limiter = $contactFormLimiter->create($request->getClientIp());
            $limit = $limiter->consume();

            if (!$limit->isAccepted()) {
                $this->addFlash('danger', 'Trop de messages envoyés. Veuillez patienter.');

                return $this->redirectToRoute('app_contact_form');
            }

            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_contact_form');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
