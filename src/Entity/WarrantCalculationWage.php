<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Codebook\Country;
use App\Entity\Codebook\Currency;
use App\Repository\WarrantCalculationWageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantCalculationWageRepository::class)]
#[ApiResource]
class WarrantCalculationWage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?Country $country = null;

    #[ORM\ManyToOne(inversedBy: 'warrantCalculationWages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantCalculation $warrantCalculation = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?float $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?Currency $currency = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?float $numberOfWages = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getWarrantCalculation(): ?WarrantCalculation
    {
        return $this->warrantCalculation;
    }

    public function setWarrantCalculation(?WarrantCalculation $warrantCalculation): static
    {
        $this->warrantCalculation = $warrantCalculation;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getNumberOfWages(): ?float
    {
        return $this->numberOfWages;
    }

    public function setNumberOfWages(?float $numberOfWages): static
    {
        $this->numberOfWages = $numberOfWages;

        return $this;
    }
}
