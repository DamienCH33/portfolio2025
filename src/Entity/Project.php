<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'title', length: 255, type: Types::STRING)]
    private ?string $title = null;

    #[ORM\Column(name: 'description', type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class)]
    #[ORM\OrderBy(['priority' => 'DESC'])]
    private Collection $techStack;

    #[ORM\Column(name: 'image', length: 255, type: Types::STRING)]
    private ?string $image = null;

    #[ORM\Column(name: 'logo', length: 255, type: Types::STRING, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(name: 'link', length: 255, type: Types::STRING)]
    private ?string $link = null;

    #[ORM\Column(name: 'demo_url', length: 255, type: Types::STRING, nullable: true)]
    private ?string $demoUrl = null;

    // ----- Étude de cas (page /projets/{slug}) -----
    #[ORM\Column(length: 160, unique: true, nullable: true)]
    private ?string $slug = null;

    /** Le besoin : pour qui, quel problème. */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $context = null;

    /** Les choix techniques et pourquoi. */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $approach = null;

    /** Une difficulté rencontrée et comment elle a été résolue. */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $challenge = null;

    /** Le résultat : en ligne, chiffres, qualité. */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $outcome = null;

    /** @var list<string> Captures supplémentaires (noms de fichiers dans image/projects) */
    #[ORM\Column(type: Types::JSON, options: ['default' => '[]'])]
    private array $gallery = [];

    #[ORM\Column(name: 'created_At', type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->techStack = new ArrayCollection();
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getTechStack(): Collection
    {
        return $this->techStack;
    }

    public function addTechStack(Skill $skill): self
    {
        if (!$this->techStack->contains($skill)) {
            $this->techStack->add($skill);
        }
        return $this;
    }

    public function removeTechStack(Skill $skill): self
    {
        $this->techStack->removeElement($skill);
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getDemoUrl(): ?string
    {
        return $this->demoUrl;
    }

    public function setDemoUrl(?string $demoUrl): self
    {
        $this->demoUrl = $demoUrl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): static
    {
        $this->context = self::clean($context);

        return $this;
    }

    public function getApproach(): ?string
    {
        return $this->approach;
    }

    public function setApproach(?string $approach): static
    {
        $this->approach = self::clean($approach);

        return $this;
    }

    public function getChallenge(): ?string
    {
        return $this->challenge;
    }

    public function setChallenge(?string $challenge): static
    {
        $this->challenge = self::clean($challenge);

        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): static
    {
        $this->outcome = self::clean($outcome);

        return $this;
    }

    /** @return list<string> */
    public function getGallery(): array
    {
        return $this->gallery;
    }

    /** @param list<string> $gallery */
    public function setGallery(array $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function addToGallery(string $filename): static
    {
        $this->gallery[] = $filename;

        return $this;
    }

    /** Une page détaillée existe dès que le besoin est renseigné. */
    public function hasCaseStudy(): bool
    {
        return null !== $this->slug && null !== $this->context;
    }

    private static function clean(?string $text): ?string
    {
        return null !== $text && '' !== trim($text) ? trim($text) : null;
    }
}
