<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Controller\Payment\ClosePaymentAction;
use App\Entity\Codebook\WarrantPaymentStatus;
use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"
        ),
        new GetCollection(
            uriTemplate         : '/catalog/payments',
            paginationEnabled   : false,
            normalizationContext: ['groups' => ['get_payment_catalog']],
            security            : "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROCURATOR')"
        ),
        new GetCollection(
            uriTemplate                 : '/warrant-payment-statuses/{paymentStatusId}/payments',
            uriVariables                : [
                                              'paymentStatusId' => new Link(
                                                  toProperty: 'warrantPaymentStatus',
                                                  fromClass : WarrantPaymentStatus::class
                                              ),
                                          ],
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            description                 : 'Retrieves payments by status',
            normalizationContext        : ['groups' => ['get_payments_by_payment_status']],
            security                    : "is_granted('ROLE_PROCURATOR') or is_granted('ROLE_ADMIN')"
        ),
        new Put(
            uriTemplate           : '/payments/{id}/close',
            controller            : ClosePaymentAction::class,
            denormalizationContext: ['groups' => ['put_payment_close']]
        ),
    ]
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: [
    'warrantPayments.warrant.code'                     => 'ipartial',
    'warrantPayments.warrant.travelType.code'          => 'ipartial',
    'warrantPayments.warrant.employee.name'            => 'ipartial',
    'warrantPayments.warrant.employee.surname'         => 'ipartial',
    'warrantPayments.warrant.employee.department.code' => 'ipartial',
    'warrantPayments.warrant.destination'              => 'ipartial',
    'warrantPayments.warrant.advancesRequired'         => 'ipartial',
    'warrantPayments.warrant.warrantStatus.code'       => 'ipartial'
])]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'warrantPayments.warrant.code',
        'warrantPayments.warrant.travelType.code',
        'warrantPayments.warrant.employee.name',
        'warrantPayments.warrant.employee.surname',
        'warrantPayments.warrant.employee.department.code',
        'warrantPayments.warrant.destination',
        'warrantPayments.warrant.advancesRequired',
        'warrantPayments.warrant.warrantStatus.code' => 'ASC', 'ACTIVE'
    ]
)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['get_payments_by_payment_status', 'put_payment_close', 'get_payment_catalog'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_payments_by_payment_status', 'get_payment_catalog'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    #[Groups(['get_payments_by_payment_status', 'get_payment_catalog'])]
    private ?Employee $closedBy = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantPaymentStatus $warrantPaymentStatus = null;

    #[ORM\OneToMany(mappedBy: 'payment', targetEntity: WarrantPayment::class, orphanRemoval: true)]
    #[Groups(['get_payments_by_payment_status'])]
    private Collection $warrantPayments;

    #[ORM\Column(nullable: true)]
    #[Groups(['get_payments_by_payment_status', 'get_payment_catalog'])]
    private ?\DateTimeImmutable $closedAt = null;

    public function __construct()
    {
        $this->warrantPayments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClosedBy(): ?Employee
    {
        return $this->closedBy;
    }

    public function setClosedBy(?Employee $closedBy): static
    {
        $this->closedBy = $closedBy;

        return $this;
    }

    public function getWarrantPaymentStatus(): ?WarrantPaymentStatus
    {
        return $this->warrantPaymentStatus;
    }

    public function setWarrantPaymentStatus(?WarrantPaymentStatus $warrantPaymentStatus): static
    {
        $this->warrantPaymentStatus = $warrantPaymentStatus;

        return $this;
    }

    /**
     * @return Collection<int, WarrantPayment>
     */
    public function getWarrantPayments(): Collection
    {
        return $this->warrantPayments;
    }

    public function addWarrantPayment(WarrantPayment $warrantPayment): static
    {
        if (!$this->warrantPayments->contains($warrantPayment)) {
            $this->warrantPayments->add($warrantPayment);
            $warrantPayment->setPayment($this);
        }

        return $this;
    }

    public function removeWarrantPayment(WarrantPayment $warrantPayment): static
    {
        if ($this->warrantPayments->removeElement($warrantPayment)) {
            // set the owning side to null (unless already changed)
            if ($warrantPayment->getPayment() === $this) {
                $warrantPayment->setPayment(null);
            }
        }

        return $this;
    }

    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeImmutable $closedAt): static
    {
        $this->closedAt = $closedAt;

        return $this;
    }
}
