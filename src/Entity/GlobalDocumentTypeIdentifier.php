<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\IdentifierTrait;
use App\Repository\GlobalDocumentTypeIdentifierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GlobalDocumentTypeIdentifierRepository::class)]
class GlobalDocumentTypeIdentifier
{

    use EntityTrait;
    use IdentifierTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $documentName = null;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;


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

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    public function setDocumentName(string $documentName): static
    {
        $this->documentName = $documentName;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
