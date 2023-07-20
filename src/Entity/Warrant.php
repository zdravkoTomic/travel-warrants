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
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Controller\Warrant\DownloadWarrantPdfReportAction;
use App\Entity\Codebook\App\TravelType;
use App\Entity\Codebook\App\WarrantGroupStatus;
use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Codebook\Country;
use App\Entity\Codebook\Currency;
use App\Entity\Codebook\Department;
use App\Entity\Codebook\VehicleType;
use App\Repository\WarrantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WarrantRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_warrant']]
        ),
        new Get(
            uriTemplate: '/warrants/{id}/report',
            formats    : ['pdf'],
            controller : DownloadWarrantPdfReportAction::class,
            read       : false
        ),
        new GetCollection(paginationClientItemsPerPage: true),
        new GetCollection(
            uriTemplate                 : '/employees/{employeeId}/warrant-group-statuses/{groupStatusId}/warrants',
            uriVariables                : [
                                              'employeeId' => 'employee',
                                              'groupStatusId' => 'groupStatus',
                                          ],
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            description                 : 'Retrieves user warrants in provided group status',
            normalizationContext        : ['groups' => ['get_user_group_warrants']]
        ),
        new GetCollection(
            uriTemplate                 : '/warrant-statuses/{statusId}/warrants',
            uriVariables                : [
                                              'statusId' => new Link(
                                                  toProperty: 'status',
                                                  fromClass : WarrantStatus::class
                                              ),
                                          ],
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            description                 : 'Retrieves user warrants by warrant status',
            normalizationContext        : ['groups' => ['get_user_warrants_by_status']],
            filters                     : ['offer.date_filter']
        ),
        new Post(denormalizationContext: ['groups' => ['post_warrant']]),
        new Put(),
        new Patch(
            uriTemplate           : '/warrants/{id}/change_status',
            formats               : ['json', 'jsonld'],
            description           : 'Change warrant status',
            denormalizationContext: ['groups' => ['patch_warrant_status']]
        )
    ]
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: [
    'employee.name' => 'ipartial',
    'employee.surname' => 'ipartial',
    'travelType.name' => 'ipartial',
    'destinationCountry.name' => 'ipartial',
    'status.name' => 'ipartial'
])]
class Warrant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['post_warrant', 'get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['post_warrant', 'get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?string $code = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_warrant', 'get_warrant', 'get_user_warrants_by_status', 'get_user_group_warrants'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private Employee $employee;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('get_warrant')]
    private ?Department $department = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['patch_warrant_status', 'get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?WarrantStatus $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantGroupStatus $groupStatus = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    #[Groups(['get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status'])]
    private ?TravelType $travelType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_warrant', 'get_user_group_warrants', 'get_warrant', 'get_user_warrants_by_status'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?Country $destinationCountry = null;

    #[ORM\Column(nullable: false)]
    #[Groups('get_warrant')]
    private ?float $wageAmount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('get_warrant')]
    private ?Currency $wageCurrency = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post_warrant', 'get_warrant'])]
    private ?string $departurePoint = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post_warrant', 'get_user_group_warrants', 'get_warrant'])]
    #[ApiFilter(SearchFilter::class, strategy: 'ipartial')]
    private ?string $destination = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post_warrant', 'get_warrant'])]
    private ?\DateTimeInterface $departureDate = null;

    #[ORM\Column]
    #[Groups(['post_warrant', 'get_warrant'])]
    private ?int $expectedTravelDuration = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['post_warrant', 'get_user_group_warrants', 'get_warrant'])]
    private ?string $travelPurposeDescription = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_warrant', 'get_warrant'])]
    private ?VehicleType $vehicleType = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['post_warrant', 'get_warrant'])]
    private ?string $vehicleDescription = null;

    #[ORM\Column]
    #[Groups(['post_warrant', 'get_warrant', 'get_user_warrants_by_status'])]
    private ?bool $advancesRequired = null;

    #[ORM\Column]
    #[Groups(['post_warrant', 'get_warrant', 'get_user_warrants_by_status'])]
    private ?float $advancesAmount = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['get_user_group_warrants', 'get_user_warrants_by_status'])]
    #[ApiFilter(OrderFilter::class)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?Employee $approvedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): Employee
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

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getVehicleDescription(): ?string
    {
        return $this->vehicleDescription;
    }

    /**
     * @param string|null $vehicleDescription
     */
    public function setVehicleDescription(?string $vehicleDescription): void
    {
        $this->vehicleDescription = $vehicleDescription;
    }

    public function jsonSerialize(): mixed
    {
        return [];
    }

    /**
     * @return float|null
     */
    public function getAdvancesAmount(): ?float
    {
        return $this->advancesAmount;
    }

    /**
     * @param float|null $advancesAmount
     */
    public function setAdvancesAmount(?float $advancesAmount): void
    {
        $this->advancesAmount = $advancesAmount;
    }
}
