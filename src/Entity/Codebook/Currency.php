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
use App\Repository\Codebook\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_EMPLOYEE')"),
        new GetCollection(
            uriTemplate      : '/catalog/currencies',
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
#[ApiFilter(OrderFilter::class, properties: ['code', 'codeNumeric', 'name', 'active' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['code', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
#[ApiFilter(BooleanFilter::class, properties: [
    'active'
])]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups([
        'get_warrant',
        'get_country_item',
        'get_country_wages',
        'get_predefined_expense',
        'get_warrant_calculation_preview'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups([
        'get_warrant',
        'get_country_item',
        'get_country_wages',
        'get_predefined_expense',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups([
        'get_warrant',
        'get_country_item',
        'get_country_wages',
        'get_predefined_expense',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?string $codeNumeric = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'get_warrant',
        'get_country_item',
        'get_country_wages',
        'get_predefined_expense',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_country_item', 'get_predefined_expense'])]
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

    /**
     * @return string|null
     */
    public function getCodeNumeric(): ?string
    {
        return $this->codeNumeric;
    }

    /**
     * @param string|null $codeNumeric
     */
    public function setCodeNumeric(?string $codeNumeric): void
    {
        $this->codeNumeric = $codeNumeric;
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
