<?php

namespace App\Entity\Codebook;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\VehicleTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleTypeRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_EMPLOYEE')"),
        new GetCollection(
            uriTemplate      : '/catalog/vehicle-types',
            paginationEnabled: false,
            security         : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            security                    : "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"
        ),
        new Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"),
        new Put(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')")
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'code', 'name', 'active' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['code', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
#[ApiFilter(BooleanFilter::class, properties: [
    'active'
])]
class VehicleType
{
    public const PERSONAL_VEHICLE = 'PERSONAL_VEHICLE';

    public const OFFICAL_PERSONAL_VEHICLE = 'OFFICAL_PERSONAL_VEHICLE';

    public const OFFICAL_VEHICLE = 'OFFICAL_VEHICLE';

    public const OTHER_VEHICLE = 'OTHER_VEHICLE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_warrant_calculation'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_warrant_calculation'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_warrant_calculation', 'get_warrant_calculation_preview'])]
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
