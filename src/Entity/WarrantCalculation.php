<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Codebook\VehicleType;
use App\Repository\WarrantCalculationRepository;
use App\Validator as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WarrantCalculationRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_warrant_calculation']]
        ),
        new Get(
            uriTemplate         : '/preview-warrant-calculations/{id}',
            normalizationContext: ['groups' => ['get_warrant_calculation_preview']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['get_warrant_calculation']],
            security            : "is_granted('ROLE_EMPLOYEE')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['post_warrant_calculation']],
            security              : "is_granted('ROLE_EMPLOYEE')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['put_warrant_calculation']],
            security              : "is_granted('ROLE_EMPLOYEE')"
        ),
        new Delete(security: "is_granted('ROLE_EMPLOYEE')")
    ]
)]
#[AppAssert\TravelItineraryOverlap]
#[AppAssert\TravelItinerary]
#[AppAssert\WarrantCalculationPersonalVehicleType]
#[AppAssert\WarrantCalculationOtherVehicleType]
class WarrantCalculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_user_group_warrants',
        'get_warrant_calculation',
        'get_warrant_calculation_preview',
        'get_approving_warrants',
        'get_user_warrants_by_status',
        'get_payments_by_payment_status',
        'get_all_warrants'
    ])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'warrantCalculation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview',
        'get_payments_by_payment_status'
    ])]
    private ?Warrant $warrant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview',
        'get_payments_by_payment_status'
    ])]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $departureDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $returningDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?\DateTimeInterface $domicileCountryLeavingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?\DateTimeInterface $domicileCountryReturningDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?VehicleType $travelVehicleType = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?string $travelVehicleDescription = null;

    #[ORM\Column(nullable: true)]
    private ?int $travelDuration = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?string $travelVehicleRegistration = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?string $travelVehicleBrand = null;

    #[ORM\Column(length: 1000)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?string $travelReport = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?int $odometerStart = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private ?int $odometerEnd = null;

    #[ORM\OneToMany(
        mappedBy     : 'warrantCalculation',
        targetEntity : WarrantTravelItinerary::class,
        cascade      : ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private Collection $warrantTravelItineraries;

    #[ORM\OneToMany(
        mappedBy     : 'warrantCalculation',
        targetEntity : WarrantCalculationExpense::class,
        cascade      : ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    private Collection $warrantCalculationExpenses;

    #[ORM\OneToMany(
        mappedBy     : 'warrantCalculation',
        targetEntity : WarrantCalculationWage::class,
        cascade      : ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['get_warrant_calculation_preview'])]
    private Collection $warrantCalculationWages;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'post_warrant_calculation',
        'put_warrant_calculation',
        'get_warrant_calculation',
        'get_warrant_calculation_preview'
    ])]
    #[Assert\NotBlank]
    private ?WageType $wageType = null;

    public function __construct()
    {
        $this->warrantTravelItineraries   = new ArrayCollection();
        $this->warrantCalculationExpenses = new ArrayCollection();
        $this->warrantCalculationWages    = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarrant(): ?Warrant
    {
        return $this->warrant;
    }

    public function setWarrant(Warrant $warrant): static
    {
        $this->warrant = $warrant;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeInterface $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getReturningDate(): ?\DateTimeInterface
    {
        return $this->returningDate;
    }

    public function setReturningDate(\DateTimeInterface $returningDate): static
    {
        $this->returningDate = $returningDate;

        return $this;
    }

    public function getDomicileCountryLeavingDate(): ?\DateTimeInterface
    {
        return $this->domicileCountryLeavingDate;
    }

    public function setDomicileCountryLeavingDate(?\DateTimeInterface $domicileCountryLeavingDate): static
    {
        $this->domicileCountryLeavingDate = $domicileCountryLeavingDate;

        return $this;
    }

    public function getDomicileCountryReturningDate(): ?\DateTimeInterface
    {
        return $this->domicileCountryReturningDate;
    }

    public function setDomicileCountryReturningDate(?\DateTimeInterface $domicileCountryReturningDate): static
    {
        $this->domicileCountryReturningDate = $domicileCountryReturningDate;

        return $this;
    }

    public function getTravelVehicleType(): ?VehicleType
    {
        return $this->travelVehicleType;
    }

    public function setTravelVehicleType(?VehicleType $travelVehicleType): static
    {
        $this->travelVehicleType = $travelVehicleType;

        return $this;
    }

    public function getTravelVehicleDescription(): ?string
    {
        return $this->travelVehicleDescription;
    }

    public function setTravelVehicleDescription(?string $travelVehicleDescription): static
    {
        $this->travelVehicleDescription = $travelVehicleDescription;

        return $this;
    }

    public function getTravelDuration(): ?int
    {
        return $this->travelDuration;
    }

    public function setTravelDuration(int $travelDuration): static
    {
        $this->travelDuration = $travelDuration;

        return $this;
    }

    public function getTravelVehicleRegistration(): ?string
    {
        return $this->travelVehicleRegistration;
    }

    public function setTravelVehicleRegistration(?string $travelVehicleRegistration): static
    {
        $this->travelVehicleRegistration = $travelVehicleRegistration;

        return $this;
    }

    public function getTravelVehicleBrand(): ?string
    {
        return $this->travelVehicleBrand;
    }

    public function setTravelVehicleBrand(?string $travelVehicleBrand): static
    {
        $this->travelVehicleBrand = $travelVehicleBrand;

        return $this;
    }

    public function getTravelReport(): ?string
    {
        return $this->travelReport;
    }

    public function setTravelReport(string $travelReport): static
    {
        $this->travelReport = $travelReport;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOdometerStart(): ?int
    {
        return $this->odometerStart;
    }

    /**
     * @param int|null $odometerStart
     */
    public function setOdometerStart(?int $odometerStart): static
    {
        $this->odometerStart = $odometerStart;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOdometerEnd(): ?int
    {
        return $this->odometerEnd;
    }

    /**
     * @param int|null $odometerEnd
     */
    public function setOdometerEnd(?int $odometerEnd): static
    {
        $this->odometerEnd = $odometerEnd;

        return $this;
    }

    /**
     * @return Collection<int, WarrantTravelItinerary>
     */
    public function getWarrantTravelItineraries(): Collection
    {
        return $this->warrantTravelItineraries;
    }

    public function addWarrantTravelItinerary(WarrantTravelItinerary $warrantTravelItinerary): static
    {
        if (!$this->warrantTravelItineraries->contains($warrantTravelItinerary)) {
            $this->warrantTravelItineraries->add($warrantTravelItinerary);
            $warrantTravelItinerary->setWarrantCalculation($this);
        }

        return $this;
    }

    public function removeWarrantTravelItinerary(WarrantTravelItinerary $warrantTravelItinerary): static
    {
        if ($this->warrantTravelItineraries->removeElement($warrantTravelItinerary)) {
            // set the owning side to null (unless already changed)
            if ($warrantTravelItinerary->getWarrantCalculation() === $this) {
                $warrantTravelItinerary->setWarrantCalculation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WarrantCalculationExpense>
     */
    public function getWarrantCalculationExpenses(): Collection
    {
        return $this->warrantCalculationExpenses;
    }

    public function addWarrantCalculationExpense(WarrantCalculationExpense $warrantCalculationExpense): static
    {
        if (!$this->warrantCalculationExpenses->contains($warrantCalculationExpense)) {
            $this->warrantCalculationExpenses->add($warrantCalculationExpense);
            $warrantCalculationExpense->setWarrantCalculation($this);
        }

        return $this;
    }

    public function removeWarrantCalculationExpense(WarrantCalculationExpense $warrantCalculationExpense): static
    {
        if ($this->warrantCalculationExpenses->removeElement($warrantCalculationExpense)) {
            // set the owning side to null (unless already changed)
            if ($warrantCalculationExpense->getWarrantCalculation() === $this) {
                $warrantCalculationExpense->setWarrantCalculation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WarrantCalculationWage>
     */
    public function getWarrantCalculationWages(): Collection
    {
        return $this->warrantCalculationWages;
    }

    public function addWarrantCalculationWage(WarrantCalculationWage $warrantCalculationWage): static
    {
        if (!$this->warrantCalculationWages->contains($warrantCalculationWage)) {
            $this->warrantCalculationWages->add($warrantCalculationWage);
            $warrantCalculationWage->setWarrantCalculation($this);
        }

        return $this;
    }

    public function removeWarrantCalculationWage(WarrantCalculationWage $warrantCalculationWage): static
    {
        if ($this->warrantCalculationWages->removeElement($warrantCalculationWage)) {
            // set the owning side to null (unless already changed)
            if ($warrantCalculationWage->getWarrantCalculation() === $this) {
                $warrantCalculationWage->setWarrantCalculation(null);
            }
        }

        return $this;
    }

    public function getWageType(): ?WageType
    {
        return $this->wageType;
    }

    public function setWageType(?WageType $wageType): static
    {
        $this->wageType = $wageType;

        return $this;
    }
}
