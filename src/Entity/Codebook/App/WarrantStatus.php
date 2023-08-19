<?php

namespace App\Entity\Codebook\App;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\App\WarrantStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantStatusRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Get(
            uriTemplate         : '/warrant-statuses/code/{code}',
            uriVariables        : ['code' => 'code'],
            paginationEnabled   : false,
            normalizationContext: ['groups' => ['get_warrant_status_by_code']],
            security            : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(),
        new Post(),
        new Put()
    ]
)]
class WarrantStatus
{
    public const NEW                           = 'NEW';
    public const APPROVING                     = 'APPROVING';
    public const APPROVING_ADVANCE_PAYMENT     = 'APPROVING_ADVANCE_PAYMENT';
    public const ADVANCE_IN_PAYMENT            = 'ADVANCE_IN_PAYMENT';
    public const CALCULATION_EDIT              = 'CALCULATION_EDIT';
    public const APPROVING_CALCULATION         = 'APPROVING_CALCULATION';
    public const APPROVING_CALCULATION_PAYMENT = 'APPROVING_CALCULATION_PAYMENT';
    public const CALCULATION_IN_PAYMENT        = 'CALCULATION_IN_PAYMENT';
    public const CLOSED                        = 'CLOSED';
    public const CANCELLED                     = 'CANCELLED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups([
        'get_user_group_warrants',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_warrant_status_by_code',
        'get_payments_by_payment_status'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status', 'get_warrant_status_by_code', 'get_payments_by_payment_status'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status', 'get_warrant_status_by_code', 'get_payments_by_payment_status'])]
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
