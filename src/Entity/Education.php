<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'year_start', type: Types::INTEGER)]
    private ?int $yearStart = null;

    #[ORM\Column(name: 'year_end', type: Types::INTEGER, nullable: true)]
    private ?int $yearEnd = null;

    #[ORM\Column(name: 'institution', length: 255, type: Types::STRING)]
    private ?string $institution = null;

    #[ORM\Column(name: 'title', length: 255, type: Types::STRING)]
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearStart(): ?int
    {
        return $this->yearStart;
    }

    public function setYearStart(int $yearStart): self
    {
        $this->yearStart = $yearStart;

        return $this;
    }

    public function getYearEnd(): ?int
    {
        return $this->yearEnd;
    }

    public function setYearEnd(int $yearEnd): self
    {
        $this->yearEnd = $yearEnd;

        return $this;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): self
    {
        $this->institution = $institution;

        return $this;
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
}
