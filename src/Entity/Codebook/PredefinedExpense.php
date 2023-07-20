<?php

namespace App\Entity\Codebook;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\PredefinedExpenseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PredefinedExpenseRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            normalizationContext        : ['groups' => ['get_predefined_expense']],
            security                    : "is_granted('ROLE_ADMIN')"
        ),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')")
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['expense.name', 'amount', 'currency.name' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['expense', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
class PredefinedExpense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_predefined_expense'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_predefined_expense'])]
    private ?ExpenseType $expense = null;

    #[ORM\Column]
    #[Groups(['get_predefined_expense'])]
    private ?float $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_predefined_expense'])]
    private ?Currency $currency = null;

    #[ORM\Column]
    #[Groups(['get_predefined_expense'])]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpense(): ?ExpenseType
    {
        return $this->expense;
    }

    public function setExpense(?ExpenseType $expense): static
    {
        $this->expense = $expense;

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
