<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Codebook\VehicleType;
use App\Repository\WarrantCalculationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantCalculationRepository::class)]
#[ApiResource]
class WarrantCalculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'warrantCalculation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warrant $warrant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departureDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $returningDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $domicileCountryLeavingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $domicileCountryReturningDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehicleType $travelVehicleType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $travelVehicleDescription = null;

    #[ORM\Column]
    private ?int $travelDuration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $travelVehicleRegistration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $travelVehicleBrand = null;

    #[ORM\Column(length: 1000)]
    private ?string $travelReport = null;

    #[ORM\Column]
    private ?int $odometerStart = null;

    /**
     * @return int|null
     */
    public function getOdometerStart(): ?int
    {
        return $this->odometerStart;
    }

    #[ORM\Column]
    private ?int $odometerEnd = null;

    #[ORM\OneToMany(mappedBy: 'warrantCalculation', targetEntity: WarrantTravelItinerary::class, orphanRemoval: true)]
    private Collection $warrantTravelItineraries;

    #[ORM\OneToMany(mappedBy: 'warrantCalculation', targetEntity: WarrantCalculationExpense::class, orphanRemoval: true)]
    private Collection $warrantCalculationExpenses;

    #[ORM\OneToMany(mappedBy: 'warrantCalculation', targetEntity: TravelCalculationWage::class, orphanRemoval: true)]
    private Collection $travelCalculationWages;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WageType $wageType = null;

    public function __construct()
    {
        $this->warrantTravelItineraries = new ArrayCollection();
        $this->warrantCalculationExpenses = new ArrayCollection();
        $this->travelCalculationWages = new ArrayCollection();
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
     * @return Collection<int, TravelCalculationWage>
     */
    public function getTravelCalculationWages(): Collection
    {
        return $this->travelCalculationWages;
    }

    public function addTravelCalculationWage(TravelCalculationWage $travelCalculationWage): static
    {
        if (!$this->travelCalculationWages->contains($travelCalculationWage)) {
            $this->travelCalculationWages->add($travelCalculationWage);
            $travelCalculationWage->setWarrantCalculation($this);
        }

        return $this;
    }

    public function removeTravelCalculationWage(TravelCalculationWage $travelCalculationWage): static
    {
        if ($this->travelCalculationWages->removeElement($travelCalculationWage)) {
            // set the owning side to null (unless already changed)
            if ($travelCalculationWage->getWarrantCalculation() === $this) {
                $travelCalculationWage->setWarrantCalculation(null);
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
