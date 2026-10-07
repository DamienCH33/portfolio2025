<?php

namespace App\Service;

use App\Entity\Contact;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Prévient l'administrateur par e-mail à chaque nouveau message du formulaire de contact.
 * Le message reste de toute façon enregistré dans le back-office.
 */
class ContactNotifier
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        #[Autowire('%env(CONTACT_RECIPIENT)%')]
        private readonly string $recipient,
        #[Autowire('%env(MAILER_FROM)%')]
        private readonly string $sender,
    ) {
    }

    public function notify(Contact $contact): bool
    {
        if ('' === trim($this->recipient)) {
            $this->logger->warning('CONTACT_RECIPIENT vide : aucun e-mail envoyé pour le nouveau message de contact.');

            return false;
        }

        $name = trim($contact->getFirstname().' '.$contact->getLastname());

        $email = (new Email())
            ->from(Address::create($this->sender))
            ->to($this->recipient)
            ->replyTo(new Address((string) $contact->getEmail(), $name))
            ->subject(sprintf('Nouveau message de %s via le portfolio', $name))
            ->text(sprintf(
                "Nom : %s\nE-mail : %s\nReçu le : %s\n\n%s\n\n—\nRépondez directement à ce mail pour écrire à %s.",
                $name,
                $contact->getEmail(),
                ($contact->getCreatedAt() ?? new \DateTimeImmutable())->format('d/m/Y à H:i'),
                $contact->getMessage(),
                $name,
            ));

        try {
            $this->mailer->send($email);

            return true;
        } catch (\Throwable $e) {
            $this->logger->error('E-mail de contact non envoyé', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
