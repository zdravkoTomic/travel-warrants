<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Codebook\App\WarrantStatus;
use App\Repository\WarrantStatusFlowRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: WarrantStatusFlowRepository::class)]
#[ApiResource]
class WarrantStatusFlow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'warrantStatusFlows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warrant $warrant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantStatus $warrantStatus = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $createdBy = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarrant(): ?Warrant
    {
        return $this->warrant;
    }

    public function setWarrant(?Warrant $warrant): static
    {
        $this->warrant = $warrant;

        return $this;
    }

    public function getWarrantStatus(): ?WarrantStatus
    {
        return $this->warrantStatus;
    }

    public function setWarrantStatus(?WarrantStatus $warrantStatus): static
    {
        $this->warrantStatus = $warrantStatus;

        return $this;
    }

    public function getCreatedBy(): ?Employee
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Employee $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
