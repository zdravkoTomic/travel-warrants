<?php

namespace App\Entity\Codebook;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\WarrantPaymentStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantPaymentStatusRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Get(
            uriTemplate         : '/warrant-payment-statuses/code/{code}',
            uriVariables        : ['code' => 'code'],
            paginationEnabled   : false,
            normalizationContext: ['groups' => ['get_warrant_payment_status_by_code']],
            security            : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(),
        new Post(),
        new Put()
    ]
)]
class WarrantPaymentStatus
{
    public const OPENED = 'OPENED';

    public const CLOSED = 'CLOSED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_warrant_payment_status_by_code'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['get_warrant_payment_status_by_code'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_warrant_payment_status_by_code'])]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
