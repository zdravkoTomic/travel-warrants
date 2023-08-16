<?php

namespace App\Entity\Codebook;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\CountryWageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CountryWageRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_country_wages']],
            security            : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            normalizationContext        : ['groups' => ['get_country_wages']],
            security                    : "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"
        ),
        new Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"),
        new Put(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')")
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'country.code', 'country.name', 'currency.code', 'currency.name', 'active' => 'ASC', 'ACTIVE'
    ]
)]
#[UniqueEntity(
    fields   : ['country', 'active'],
    message  : 'Only one active record per country.',
    errorPath: 'country',
)]
class CountryWage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_country_item', 'get_country_wages'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_country_wages', 'post_country_wage'])]
    private ?Country $country = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_country_item', 'get_country_wages'])]
    private ?Currency $currency = null;

    #[ORM\Column]
    #[Groups(['get_country_item', 'get_country_wages'])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $amount = null;

    #[ORM\Column]
    #[Groups(['get_country_item', 'get_country_wages'])]
    private ?bool $active = null;

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

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

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
