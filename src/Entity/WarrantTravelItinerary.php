<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Codebook\Country;
use App\Repository\WarrantTravelItineraryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarrantTravelItineraryRepository::class)]
#[ApiResource]
class WarrantTravelItinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'warrantTravelItineraries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WarrantCalculation $warrantCalculation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $EnteredDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $exitedDate = null;

    #[ORM\Column]
    private ?bool $returningData = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeSpent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarrantCalculation(): ?WarrantCalculation
    {
        return $this->warrantCalculation;
    }

    public function setWarrantCalculation(?WarrantCalculation $warrantCalculation): static
    {
        $this->warrantCalculation = $warrantCalculation;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getEnteredDate(): ?\DateTimeInterface
    {
        return $this->EnteredDate;
    }

    public function setEnteredDate(\DateTimeInterface $EnteredDate): static
    {
        $this->EnteredDate = $EnteredDate;

        return $this;
    }

    public function getExitedDate(): ?\DateTimeInterface
    {
        return $this->exitedDate;
    }

    public function setExitedDate(\DateTimeInterface $exitedDate): static
    {
        $this->exitedDate = $exitedDate;

        return $this;
    }

    public function isReturningData(): ?bool
    {
        return $this->returningData;
    }

    public function setReturningData(bool $returningData): static
    {
        $this->returningData = $returningData;

        return $this;
    }

    public function getTimeSpent(): ?int
    {
        return $this->timeSpent;
    }

    public function setTimeSpent(?int $timeSpent): static
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }
}
