<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\IdentifierTrait;
use App\Repository\GlobalLocationNumberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GlobalLocationNumberRepository::class)]
class GlobalLocationNumber
{
    use EntityTrait;
    use IdentifierTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $locationName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $gps = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locationAddress = null;

    #[ORM\ManyToOne(inversedBy: 'glns')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;


    public function __construct()
    {
        
        $this->code = uniqid();
        $this->enabled = true;
        $this->deleted = false;
        $this->createdAt = new \DateTimeImmutable();
        
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocationName(): ?string
    {
        return $this->locationName;
    }

    public function setLocationName(string $locationName): static
    {
        $this->locationName = $locationName;

        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(?string $gps): static
    {
        $this->gps = $gps;

        return $this;
    }

    public function getLocationAddress(): ?string
    {
        return $this->locationAddress;
    }

    public function setLocationAddress(?string $locationAddress): static
    {
        $this->locationAddress = $locationAddress;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }
}
