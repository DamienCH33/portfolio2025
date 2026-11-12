<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\ContactNotifier;
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
        RateLimiterFactory $contactFormLimiter,
        ContactNotifier $notifier,
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

            // Le profil choisi est noté en tête du message : visible dans le back-office et dans l'e-mail.
            $audience = $form->get('audience')->getData();
            if (is_string($audience) && $audience !== '') {
                $contact->setMessage('[' . $audience . '] ' . $contact->getMessage());
            }

            $em->persist($contact);
            $em->flush();

            // E-mail à l'administrateur (le message est déjà enregistré dans le back-office).
            $notifier->notify($contact);

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_contact_form');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
