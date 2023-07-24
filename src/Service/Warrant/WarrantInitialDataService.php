<?php

namespace App\Service\Warrant;

use App\Entity\Codebook\App\TravelType;
use App\Entity\Codebook\App\WarrantGroupStatus;
use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use App\Repository\Codebook\App\TravelTypeRepository;
use App\Repository\Codebook\App\WarrantGroupStatusRepository;
use App\Repository\Codebook\App\WarrantStatusRepository;
use App\Repository\Codebook\CountryWageRepository;
use App\Repository\WarrantRepository;
use Doctrine\ORM\NonUniqueResultException;

class WarrantInitialDataService
{
    private const WARRANT_CODE_PREFIX = 'PNL';

    private WarrantStatusRepository $warrantStatusRepository;
    private WarrantGroupStatusRepository $warrantGroupStatusRepository;
    private CountryWageRepository $countryWageRepository;
    private TravelTypeRepository $travelTypeRepository;
    private WarrantRepository $warrantRepository;

    public function __construct(
        WarrantStatusRepository      $warrantStatusRepository,
        WarrantGroupStatusRepository $warrantGroupStatusRepository,
        CountryWageRepository        $countryWageRepository,
        TravelTypeRepository         $travelTypeRepository,
        WarrantRepository            $warrantRepository
    ) {
        $this->warrantStatusRepository      = $warrantStatusRepository;
        $this->warrantGroupStatusRepository = $warrantGroupStatusRepository;
        $this->countryWageRepository        = $countryWageRepository;
        $this->travelTypeRepository         = $travelTypeRepository;
        $this->warrantRepository            = $warrantRepository;
    }

    /**
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function setInitialData(Warrant $warrant): void
    {
        $initialWarrantStatus      = $this->warrantStatusRepository->findExistingByCode(WarrantStatus::NEW);
        $initialWarrantGroupStatus = $this->warrantGroupStatusRepository->findExistingByCode(
            WarrantGroupStatus::INITIAL
        );

        if (!$warrant->getDestinationCountry()) {
            throw new RecordNotFoundException($warrant->getDestinationCountry());
        }

        $countryWage = $this->countryWageRepository->findActiveByCountryId(
            $warrant->getDestinationCountry()->getId()
        );

        if (!$countryWage || !$countryWage->getCountry()) {
            throw new RecordNotFoundException(get_class($warrant));
        }

        $travelType = $countryWage->getCountry()->isDomicile()
            ? $this->travelTypeRepository->findExistingByCode(TravelType::DOMESTIC)
            : $this->travelTypeRepository->findExistingByCode(TravelType::INTERNATIONAL);

        if (!$countryWage->getCurrency()) {
            throw new RecordNotFoundException(get_class($countryWage->getCurrency()));
        }

        if (!$warrant->getEmployee()) {
            throw new RecordNotFoundException(get_class($warrant->getEmployee()));
        }

        $advanceRequired = (bool)$warrant->getAdvancesAmount();
        $domicileCurrency = $this->countryWageRepository->getDomicileWageCurrency()->getCurrency();

        $warrant->setStatus($initialWarrantStatus)
            ->setGroupStatus($initialWarrantGroupStatus)
            ->setWageAmount($countryWage->getAmount())
            ->setWageCurrency($countryWage->getCurrency())
            ->setTravelType($travelType)
            ->setAdvancesRequired($advanceRequired)
            ->setAdvancesCurrency($domicileCurrency)
            ->setDepartment($warrant->getEmployee()->getDepartment())
            ->setCode($this->generateWarrantCode());
    }

    private function generateWarrantCode(): string
    {
        return sprintf(
            '%s%d',
            self::WARRANT_CODE_PREFIX,
            $this->warrantRepository->getNewWarrantOrdinalNumber()
        );
    }
}