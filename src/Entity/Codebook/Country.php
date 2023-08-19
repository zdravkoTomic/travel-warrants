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
use App\Repository\Codebook\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_EMPLOYEE')"),
        new GetCollection(
            uriTemplate      : '/catalog/countries',
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
#[ApiFilter(OrderFilter::class, properties: ['code', 'name', 'domicile', 'active' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['code', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
#[ApiFilter(BooleanFilter::class, properties: [
    'countryWages.active', 'active', 'domicile'
])]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups([
        'get_warrant',
        'get_user_group_warrants',
        'get_user_warrants_by_status',
        'get_country_item',
        'get_country_wages',
        'get_warrant_calculation_preview',
        'get_payments_by_payment_status'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups([
        'get_warrant',
        'get_user_group_warrants',
        'get_user_warrants_by_status',
        'get_country_item',
        'get_country_wages',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'get_warrant',
        'get_user_group_warrants',
        'get_user_warrants_by_status',
        'get_country_item',
        'get_country_wages',
        'get_warrant_calculation_preview',
        'get_payments_by_payment_status'
    ])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_country_wages'])]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $domicile = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: CountryWage::class)]
    #[Groups(['get_country_item'])]
    private Collection $countryWages;

    #[Groups(['get_country_item'])]
    private ?bool $definedWage = null;

    public function __construct()
    {
        $this->countryWages = new ArrayCollection();
    }

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

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isDomicile(): ?bool
    {
        return $this->domicile;
    }

    /**
     * @param bool|null $domicile
     */
    public function setDomicile(?bool $domicile): static
    {
        $this->domicile = $domicile;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDefinedWage(): ?bool
    {
        return $this->definedWage;
    }

    /**
     * @param bool|null $definedWage
     */
    public function setDefinedWage(?bool $definedWage = false): static
    {
        $this->definedWage = $definedWage;

        foreach ($this->getCountryWages() as $countryWage) {
            if ($countryWage->isActive()) {
                $this->definedWage = true;
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CountryWage>
     */
    public function getCountryWages(): Collection
    {
        return $this->countryWages;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}
