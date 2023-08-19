<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\WarrantPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantPaymentRepository::class)]
#[ApiResource]
class WarrantPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_payments_by_payment_status'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_payments_by_payment_status'])]
    private ?Warrant $warrant = null;

    #[ORM\ManyToOne(inversedBy: 'warrantPayments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Payment $payment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_payments_by_payment_status'])]
    private ?PaymentType $paymentType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarrant(): ?Warrant
    {
        return $this->warrant;
    }

    public function setWarrant(?Warrant $warrant): static
    {
        $this->warrant = $warrant;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }
}
