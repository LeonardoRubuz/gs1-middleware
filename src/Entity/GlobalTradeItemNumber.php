<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\IdentifierTrait;
use App\Repository\GlobalTradeItemNumberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GlobalTradeItemNumberRepository::class)]
class GlobalTradeItemNumber
{
    use EntityTrait;
    use IdentifierTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $itemName = null;

    #[ORM\ManyToOne(inversedBy: 'globalTradeItemNumbers')]
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

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): static
    {
        $this->itemName = $itemName;

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
