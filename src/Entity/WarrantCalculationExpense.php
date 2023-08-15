<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Codebook\Currency;
use App\Entity\Codebook\ExpenseType;
use App\Repository\WarrantCalculationExpenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarrantCalculationExpenseRepository::class)]
#[ApiResource]
class WarrantCalculationExpense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'warrantCalculationExpenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantCalculation $warrantCalculation = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $currency = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExpenseType $expenseType = null;

    #[ORM\Column]
    private ?float $originalAmount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $originalCurrency = null;

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
    public function setOriginalCurrency(?Currency $originalCurrency): void
    {
        $this->originalCurrency = $originalCurrency;
    }
}
