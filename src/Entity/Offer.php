<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Une offre affichée sur la page Services, gérée depuis le back-office.
 */
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    #[Assert\Length(max: 120)]
    private string $title = '';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Une phrase de présentation est obligatoire.')]
    #[Assert\Length(max: 255)]
    private string $summary = '';

    /** Une ligne = un élément de la liste (inclus, formules…). */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 3000)]
    private ?string $items = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: 'Le prix est obligatoire.')]
    #[Assert\Length(max: 120)]
    private string $priceLabel = '';

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $note = null;

    /** Classe d'icône Bootstrap Icons, ex. « bi-window ». */
    #[ORM\Column(length: 60)]
    #[Assert\Regex(pattern: '/^bi-[a-z0-9-]+$/', message: 'Icône invalide (ex. bi-window).')]
    private string $icon = 'bi-stars';

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = trim((string) $title);
        $this->touch();

        return $this;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = trim((string) $summary);
        $this->touch();

        return $this;
    }

    public function getItems(): ?string
    {
        return $this->items;
    }

    public function setItems(?string $items): static
    {
        $this->items = null !== $items && '' !== trim($items) ? trim($items) : null;
        $this->touch();

        return $this;
    }

    /** @return list<string> */
    public function getItemList(): array
    {
        if (null === $this->items) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\R/', $this->items) ?: [])));
    }

    public function getPriceLabel(): string
    {
        return $this->priceLabel;
    }

    public function setPriceLabel(?string $priceLabel): static
    {
        $this->priceLabel = trim((string) $priceLabel);
        $this->touch();

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = null !== $note && '' !== trim($note) ? trim($note) : null;
        $this->touch();

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = trim((string) $icon) ?: 'bi-stars';
        $this->touch();

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = (int) $position;
        $this->touch();

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        $this->touch();

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
