<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait IdentifierTrait 
{
    
    #[ORM\Column(type: 'string', length: 10)]
    private string $applicationIdentifier;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $value;

    public function getApplicationIdentifier(): string
    {
        return $this->applicationIdentifier;
    }

    public function setApplicationIdentifier(string $applicationIdentifier): self
    {
        $this->applicationIdentifier = $applicationIdentifier;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

}