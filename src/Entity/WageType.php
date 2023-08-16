<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\WageTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WageTypeRepository::class)]
#[ApiResource]
class WageType
{
    public const FULL_WAGE        = 'FULL_WAGE';
    public const ONE_MEAL_COVERED = 'ONE_MEAL_COVERED';
    public const TWO_MEAL_COVERED = 'TWO_MEAL_COVERED';
    public const NO_WAGE          = 'NO_WAGE';
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
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
