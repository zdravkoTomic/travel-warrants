<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\WageTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WageTypeRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_EMPLOYEE')"),
        new GetCollection(
            uriTemplate      : '/catalog/wage-types',
            paginationEnabled: false,
            security         : "is_granted('ROLE_EMPLOYEE')"
        )
    ]
)]
class WageType
{
    public const FULL_WAGE        = 'FULL_WAGE';

    public const ONE_MEAL_COVERED = 'ONE_MEAL_COVERED';

    public const TWO_MEAL_COVERED = 'TWO_MEAL_COVERED';

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
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_warrant_calculation'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_warrant_calculation'])]
    private ?int $wagePercentageDeduction = null;

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

    public function getWagePercentageDeduction(): ?int
    {
        return $this->wagePercentageDeduction;
    }

    public function setWagePercentageDeduction(int $wagePercentageDeduction): static
    {
        $this->wagePercentageDeduction = $wagePercentageDeduction;

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
