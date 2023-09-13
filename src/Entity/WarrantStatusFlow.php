<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Codebook\App\WarrantStatus;
use App\Repository\WarrantStatusFlowRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantStatusFlowRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(
            normalizationContext        : ['groups' => ['get_warrant_status_flow']],
            security                    : "is_granted('ROLE_ADMIN')"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'warrant.id' => 'exact'
])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt' => 'ASC', 'ACTIVE'])]
class WarrantStatusFlow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_warrant_status_flow'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'warrantStatusFlows')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_warrant_status_flow'])]
    private ?Warrant $warrant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_warrant_status_flow'])]
    private ?WarrantStatus $warrantStatus = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_warrant_status_flow'])]
    private ?Employee $createdBy = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['get_warrant_status_flow'])]
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
