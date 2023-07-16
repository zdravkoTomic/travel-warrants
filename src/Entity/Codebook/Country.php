<?php

namespace App\Entity\Codebook;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\CountryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            paginationEnabled: true,
            paginationClientItemsPerPage: true
        ),
        new Post(),
        new Put()
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['code', 'name' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['code', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_user_warrants_by_status'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_user_warrants_by_status'])]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_warrant', 'get_user_group_warrants', 'get_user_warrants_by_status'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $domicile = null;

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
    public function setDomicile(?bool $domicile): void
    {
        $this->domicile = $domicile;
    }
}
