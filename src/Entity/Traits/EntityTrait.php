<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait EntityTrait 
{
    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $code;
    
    #[ORM\Column(type: 'boolean')]
    private bool $enabled;
    
    #[ORM\Column(type: 'boolean')]
    private bool $deleted;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt;
    
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    public function getCode(): string
    {
        return $this->code;
    }
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }
    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }
    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }
    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

}