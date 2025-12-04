<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{

    use EntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $customer = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true, unique: true)]
    private ?string $externalId = null;

    /**
     * @var Collection<int, GlobalLocationNumber>
     */
    #[ORM\OneToMany(targetEntity: GlobalLocationNumber::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $glns;
    
    /**
     * @var Collection<int, GlobalServiceRelationNumber>
     */
    #[ORM\OneToMany(targetEntity: GlobalServiceRelationNumber::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $gsrns;
    /**
     * @var Collection<int, GlobalDocumentTypeIdentifier>
     */
    #[ORM\OneToMany(targetEntity: GlobalDocumentTypeIdentifier::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $gdtis;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $companyPrefix = null;

    /**
     * @var Collection<int, GlobalTradeItemNumber>
     */
    #[ORM\OneToMany(targetEntity: GlobalTradeItemNumber::class, mappedBy: 'project')]
    private Collection $globalTradeItemNumbers;

    public function __construct()
    {
        $this->glns = new ArrayCollection();
        $this->externalId = (string) Uuid::v4();
        $this->code = uniqid();
        $this->enabled = true;
        $this->deleted = false;
        $this->createdAt = new \DateTimeImmutable();
        $this->globalTradeItemNumbers = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(?string $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return Collection<int, GlobalLocationNumber>
     */
    public function getGlns(): Collection
    {
        return $this->glns;
    }

    public function addGln(GlobalLocationNumber $gln): static
    {
        if (!$this->glns->contains($gln)) {
            $this->glns->add($gln);
            $gln->setProject($this);
        }

        return $this;
    }

    public function removeGln(GlobalLocationNumber $gln): static
    {
        if ($this->glns->removeElement($gln)) {
            // set the owning side to null (unless already changed)
            if ($gln->getProject() === $this) {
                $gln->setProject(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, GlobalServiceRelationNumber>
     */
    public function getGsrns(): Collection
    {
        return $this->gsrns;
    }

    public function addGsrn(GlobalServiceRelationNumber $gsrn): static
    {
        if (!$this->gsrns->contains($gsrn)) {
            $this->gsrns->add($gsrn);
            $gsrn->setProject($this);
        }

        return $this;
    }

    public function removeGsrn(GlobalServiceRelationNumber $gsrn): static
    {
        if ($this->gsrns->removeElement($gsrn)) {
            // set the owning side to null (unless already changed)
            if ($gsrn->getProject() === $this) {
                $gsrn->setProject(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, GlobalDocumentTypeIdentifier>
     */
    public function getGdtis(): Collection
    {
        return $this->gdtis;
    }

    public function addGdti(GlobalDocumentTypeIdentifier $gdti): static
    {
        if (!$this->gdtis->contains($gdti)) {
            $this->gdtis->add($gdti);
            $gdti->setProject($this);
        }

        return $this;
    }

    public function removeGdti(GlobalDocumentTypeIdentifier $gdti): static
    {
        if ($this->gdtis->removeElement($gdti)) {
            // set the owning side to null (unless already changed)
            if ($gdti->getProject() === $this) {
                $gdti->setProject(null);
            }
        }

        return $this;
    }

    
    public function getCompanyPrefix(): ?string
    {
        return $this->companyPrefix;
    }

    public function setCompanyPrefix(?string $companyPrefix): static
    {
        $this->companyPrefix = $companyPrefix;

        return $this;
    }

    /**
     * @return Collection<int, GlobalTradeItemNumber>
     */
    public function getGlobalTradeItemNumbers(): Collection
    {
        return $this->globalTradeItemNumbers;
    }

    public function addGlobalTradeItemNumber(GlobalTradeItemNumber $globalTradeItemNumber): static
    {
        if (!$this->globalTradeItemNumbers->contains($globalTradeItemNumber)) {
            $this->globalTradeItemNumbers->add($globalTradeItemNumber);
            $globalTradeItemNumber->setProject($this);
        }

        return $this;
    }

    public function removeGlobalTradeItemNumber(GlobalTradeItemNumber $globalTradeItemNumber): static
    {
        if ($this->globalTradeItemNumbers->removeElement($globalTradeItemNumber)) {
            // set the owning side to null (unless already changed)
            if ($globalTradeItemNumber->getProject() === $this) {
                $globalTradeItemNumber->setProject(null);
            }
        }

        return $this;
    }
}
