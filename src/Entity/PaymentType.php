<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaymentTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentTypeRepository::class)]
#[ApiResource]
class PaymentType
{
    public const ADVANCE = 'ADVANCE';

    public const CALCULATION = 'CALCULATION';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_payments_by_payment_status'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['get_payments_by_payment_status'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_payments_by_payment_status'])]
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
