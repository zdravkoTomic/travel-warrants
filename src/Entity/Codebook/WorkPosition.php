<?php

namespace App\Entity\Codebook;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\WorkPositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WorkPositionRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            uriTemplate         : '/catalog/work-positions',
            paginationEnabled   : false
        ),
        new GetCollection(
            paginationEnabled: true,
            paginationClientItemsPerPage: true,
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')")
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['code', 'name', 'active' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['code', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
#[ApiFilter(BooleanFilter::class, properties: [
    'active'
])]
class WorkPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_employee', 'get_warrant', 'get_department'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['get_employee', 'get_warrant'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_employee', 'get_warrant', 'get_department'])]
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
