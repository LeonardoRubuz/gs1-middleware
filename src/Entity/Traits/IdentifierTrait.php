<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait IdentifierTrait 
{
    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $code;
    
    #[ORM\Column(type: 'string', length: 10)]
    private string $applicationIdentifier;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $value;
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?\DateTimeInterface $createdAt;
    
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?\DateTimeInterface $updatedAt;

}