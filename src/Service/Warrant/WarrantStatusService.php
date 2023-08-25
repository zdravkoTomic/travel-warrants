<?php

namespace App\Service\Warrant;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Entity\Codebook\App\WarrantGroupStatus;
use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use App\Repository\Codebook\App\WarrantGroupStatusRepository;
use App\Repository\WarrantRepository;
use App\Workflow\WarrantStatusTransition;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\WorkflowInterface;

class WarrantStatusService
{
    private const GROUP_INITIAL_STATUSES = [
        WarrantStatus::NEW,
        WarrantStatus::APPROVING,
        WarrantStatus::APPROVING_ADVANCE_PAYMENT,
        WarrantStatus::ADVANCE_IN_PAYMENT
    ];

    private const GROUP_CALCULATION_STATUSES = [
        WarrantStatus::CALCULATION_EDIT,
        WarrantStatus::APPROVING_CALCULATION,
        WarrantStatus::APPROVING_CALCULATION_PAYMENT,
        WarrantStatus::CALCULATION_IN_PAYMENT,
        WarrantStatus::ADVANCE_REFUND
    ];

    private const GROUP_CLOSED_STATUSES = [
        WarrantStatus::CLOSED,
        WarrantStatus::CANCELLED
    ];

    private WarrantGroupStatusRepository $warrantGroupStatusRepository;
    private WorkflowInterface $warrantStateMachine;
    private WarrantRepository $warrantRepository;
    private WarrantExpensesService $warrantExpenses;

    public function __construct(
        WarrantGroupStatusRepository $warrantGroupStatusRepository,
        WorkflowInterface            $warrantStateMachine,
        WarrantRepository            $warrantRepository,
        WarrantExpensesService $warrantExpenses
    ) {
        $this->warrantGroupStatusRepository = $warrantGroupStatusRepository;
        $this->warrantStateMachine          = $warrantStateMachine;
        $this->warrantRepository            = $warrantRepository;
        $this->warrantExpenses = $warrantExpenses;
    }

    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function setWarrantGroupStatus(Warrant $warrant): void
    {
        if (!$warrant->getStatus()) {
            throw new InvalidArgumentException('Non existing warrant status while updating group status');
        }

        if (in_array($warrant->getStatus()->getCode(), self::GROUP_INITIAL_STATUSES, true)) {
            $warrantGroupStatus = $this->warrantGroupStatusRepository
                ->findExistingByCode(WarrantGroupStatus::INITIAL);
        } else if (in_array($warrant->getStatus()->getCode(), self::GROUP_CALCULATION_STATUSES, true)) {
            $warrantGroupStatus = $this->warrantGroupStatusRepository
                ->findExistingByCode(WarrantGroupStatus::CALCULATION);
        } else if (in_array($warrant->getStatus()->getCode(), self::GROUP_CLOSED_STATUSES, true)) {
            $warrantGroupStatus = $this->warrantGroupStatusRepository
                ->findExistingByCode(WarrantGroupStatus::CLOSED);
        } else {
            throw new InvalidArgumentException('Invalid status code provided');
        }

        $warrant->setGroupStatus($warrantGroupStatus);
    }

    public function validateAndSetStatusChange(Warrant $warrant, string $newStatusCodeTransition = null): void
    {
        if (!$this->warrantStateMachine->can($warrant, strtolower($newStatusCodeTransition))) {
            throw new ValidationException(
                sprintf(
                    'Invalid status change from %s to %s',
                    $warrant->getStatus()->getCode(),
                    $newStatusCodeTransition
                ),
                Response::HTTP_TEMPORARY_REDIRECT
            );
        }

        $advanceRefundRequired = false;

        if (strtolower($newStatusCodeTransition) === strtolower(WarrantStatusTransition::TO_CANCELLED)
            && $warrant->getGroupStatus()->getCode() === WarrantGroupStatus::CALCULATION
        ) {
            $advanceRefundRequired = $this->warrantExpenses->getAdvanceAmountInDomicileWageCurrency($warrant) > 0;
        } else if (strtolower($newStatusCodeTransition) === strtolower(WarrantStatusTransition::TO_CLOSED)) {
            $advanceRefundRequired = $this->warrantExpenses->advancesRefoundRequired($warrant);
        }

        if ($advanceRefundRequired) {
            $this->warrantStateMachine->apply($warrant, strtolower(WarrantStatusTransition::TO_ADVANCE_REFUND));
        } else {
            $this->warrantStateMachine->apply($warrant, strtolower($newStatusCodeTransition));
        }
    }

    public function updateWarrantStatus(Warrant $warrant, WarrantStatus $warrantStatus): void
    {
        $this->warrantRepository->updateWarrantStatus($warrant, $warrantStatus);
    }
}