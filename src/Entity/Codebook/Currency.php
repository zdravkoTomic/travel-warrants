<?php

namespace App\Entity\Codebook;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\Codebook\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put()
    ]
)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_warrant'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['get_warrant'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(['get_warrant'])]
    private ?int $codeNumeric = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_warrant'])]
    private ?string $name = null;

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

    /**
     * @return int|null
     */
    public function getCodeNumeric(): ?int
    {
        return $this->codeNumeric;
    }

    /**
     * @param int|null $codeNumeric
     */
    public function setCodeNumeric(?int $codeNumeric): void
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
