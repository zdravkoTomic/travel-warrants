<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Codebook\Currency;
use App\Entity\Codebook\ExpenseType;
use App\Repository\WarrantCalculationExpenseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantCalculationExpenseRepository::class)]
#[ApiResource]
class WarrantCalculationExpense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['post_warrant_calculation', 'put_warrant_calculation'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'warrantCalculationExpenses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_warrant_calculation', 'put_warrant_calculation'])]
    private ?WarrantCalculation $warrantCalculation = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?float $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['get_warrant_calculation_preview'])]
    private ?Currency $currency = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_warrant_calculation', 'put_warrant_calculation', 'get_warrant_calculation', 'get_warrant_calculation_preview'])]
    private ?ExpenseType $expenseType = null;

    #[ORM\Column]
    #[Groups(['post_warrant_calculation', 'put_warrant_calculation', 'get_warrant_calculation', 'get_warrant_calculation_preview'])]
    private ?float $originalAmount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_warrant_calculation', 'put_warrant_calculation', 'get_warrant_calculation', 'get_warrant_calculation_preview'])]
    private ?Currency $originalCurrency = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups(['post_warrant_calculation', 'put_warrant_calculation', 'get_warrant_calculation', 'get_warrant_calculation_preview'])]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setAmount(float $amount): static
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

    public function getExpenseType(): ?ExpenseType
    {
        return $this->expenseType;
    }

    public function setExpenseType(?ExpenseType $expenseType): static
    {
        $this->expenseType = $expenseType;

        return $this;
    }

    public function getOriginalAmount(): ?float
    {
        return $this->originalAmount;
    }

    public function setOriginalAmount(float $originalAmount): static
    {
        $this->originalAmount = $originalAmount;

        return $this;
    }

    /**
     * @return Currency|null
     */
    public function getOriginalCurrency(): ?Currency
    {
        return $this->originalCurrency;
    }

    /**
     * @param Currency|null $originalCurrency
     */
    public function setOriginalCurrency(?Currency $originalCurrency): static
    {
        $this->originalCurrency = $originalCurrency;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
