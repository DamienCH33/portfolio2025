<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\ManyToMany(targetEntity: Skill::class)]
    #[ORM\OrderBy(['priority' => 'DESC'])]
    private Collection $techStack;

    #[ORM\Column(name: 'image', length: 255, type: Types::STRING)]
    private ?string $image = null;

    #[ORM\Column(name: 'link', length: 255, type: Types::STRING)]
    private ?string $link = null;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
