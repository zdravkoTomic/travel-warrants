<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Codebook\Country;
use App\Entity\Codebook\Currency;
use App\Entity\Codebook\Department;
use App\Entity\Codebook\Employee;
use App\Entity\Codebook\TravelType;
use App\Entity\Codebook\VehicleType;
use App\Entity\Codebook\WarrantGroupStatus;
use App\Entity\Codebook\WarrantStatus;
use App\Repository\WarrantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarrantRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put()
    ]
)]
class Warrant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantStatus $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantGroupStatus $groupStatus = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TravelType $travelType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $destinationCountry = null;

    #[ORM\Column]
    private ?float $wageAmount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $wageCurrency = null;

    #[ORM\Column(length: 255)]
    private ?string $departurePoint = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departureDate = null;

    #[ORM\Column]
    private ?int $expectedTravelDuration = null;

    #[ORM\Column(length: 1000)]
    private ?string $travelPurposeDescription = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehicleType $vehicleType = null;

    #[ORM\Column]
    private ?bool $advancesRequired = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?Employee $approvedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getStatus(): ?WarrantStatus
    {
        return $this->status;
    }

    public function setStatus(?WarrantStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getGroupStatus(): ?WarrantGroupStatus
    {
        return $this->groupStatus;
    }

    public function setGroupStatus(?WarrantGroupStatus $groupStatus): static
    {
        $this->groupStatus = $groupStatus;

        return $this;
    }

    public function getTravelType(): ?TravelType
    {
        return $this->travelType;
    }

    public function setTravelType(?TravelType $travelType): static
    {
        $this->travelType = $travelType;

        return $this;
    }

    public function getDestinationCountry(): ?Country
    {
        return $this->destinationCountry;
    }

    public function setDestinationCountry(?Country $destinationCountry): static
    {
        $this->destinationCountry = $destinationCountry;

        return $this;
    }

    public function getWageAmount(): ?float
    {
        return $this->wageAmount;
    }

    public function setWageAmount(float $wageAmount): static
    {
        $this->wageAmount = $wageAmount;

        return $this;
    }

    public function getWageCurrency(): ?Currency
    {
        return $this->wageCurrency;
    }

    public function setWageCurrency(?Currency $wageCurrency): static
    {
        $this->wageCurrency = $wageCurrency;

        return $this;
    }

    public function getDeparturePoint(): ?string
    {
        return $this->departurePoint;
    }

    public function setDeparturePoint(string $departurePoint): static
    {
        $this->departurePoint = $departurePoint;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

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

    public function getExpectedTravelDuration(): ?int
    {
        return $this->expectedTravelDuration;
    }

    public function setExpectedTravelDuration(int $expectedTravelDuration): static
    {
        $this->expectedTravelDuration = $expectedTravelDuration;

        return $this;
    }

    public function getTravelPurposeDescription(): ?string
    {
        return $this->travelPurposeDescription;
    }

    public function setTravelPurposeDescription(string $travelPurposeDescription): static
    {
        $this->travelPurposeDescription = $travelPurposeDescription;

        return $this;
    }

    public function getVehicleType(): ?VehicleType
    {
        return $this->vehicleType;
    }

    public function setVehicleType(?VehicleType $vehicleType): static
    {
        $this->vehicleType = $vehicleType;

        return $this;
    }

    public function isAdvancesRequired(): ?bool
    {
        return $this->advancesRequired;
    }

    public function setAdvancesRequired(bool $advancesRequired): static
    {
        $this->advancesRequired = $advancesRequired;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getApprovedBy(): ?Employee
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?Employee $approvedBy): static
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }
}
