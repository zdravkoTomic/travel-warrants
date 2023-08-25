<?php

namespace App\Service\Payment;

use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Codebook\ExpenseType;
use App\Entity\Codebook\WarrantPaymentStatus;
use App\Entity\Employee;
use App\Entity\Payment;
use App\Exception\RecordNotFoundException;
use App\Repository\Codebook\App\WarrantStatusRepository;
use App\Repository\Codebook\ExpenseTypeRepository;
use App\Repository\Codebook\WarrantPaymentStatusRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class PaymentService
{
    private PaymentRepository $paymentRepository;
    private WarrantPaymentStatusRepository $warrantPaymentStatusRepository;
    private ExpenseTypeRepository $expenseTypeRepository;
    private WarrantStatusRepository $warrantStatusRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PaymentRepository              $paymentRepository,
        WarrantPaymentStatusRepository $warrantPaymentStatusRepository,
        ExpenseTypeRepository          $expenseTypeRepository,
        WarrantStatusRepository        $warrantStatusRepository,
        EntityManagerInterface         $entityManager
    ) {
        $this->paymentRepository              = $paymentRepository;
        $this->warrantPaymentStatusRepository = $warrantPaymentStatusRepository;
        $this->expenseTypeRepository          = $expenseTypeRepository;
        $this->warrantStatusRepository        = $warrantStatusRepository;
        $this->entityManager                  = $entityManager;
    }

    public function closeOpenedPayment(Payment $payment, Employee $user): void
    {
        $closedWarrantPaymentStatus = $this->warrantPaymentStatusRepository->findOneBy(
            [
                'code' => WarrantPaymentStatus::CLOSED
            ]
        );

        if (!$closedWarrantPaymentStatus) {
            throw new RuntimeException('An exception occured while fetching closed warrant payment status');
        }

        $calculationEditStatus = $this->warrantStatusRepository->findExistingByCode(
            WarrantStatus::CALCULATION_EDIT
        );

        $closedWarrantStatus = $this->warrantStatusRepository->findExistingByCode(
            WarrantStatus::CLOSED
        );

        $this->paymentRepository->closePayment($payment, $user, $closedWarrantPaymentStatus);

        foreach ($payment->getWarrantPayments() as $warrantPayment) {
            $warrant = $warrantPayment->getWarrant();

            if (!$warrant) {
                throw new RuntimeException('An exception occured while fetching warrant data');
            }

            if ($warrant->getStatus()->getCode() === WarrantStatus::ADVANCE_IN_PAYMENT) {
                $warrant->setStatus($calculationEditStatus);
            }

            if ($warrant->getStatus()->getCode() === WarrantStatus::CALCULATION_IN_PAYMENT) {
                $warrant->setStatus($closedWarrantStatus);
            }

            $this->entityManager->persist($warrant);
        }

        $this->entityManager->flush();
    }

    /**
     * @throws RecordNotFoundException
     */
    public function getPaymentExpenses(Payment $payment): ?array
    {
        $expenses = $this->paymentRepository->findPaymentExpensesById($payment->getId());

        $warrantCalculationWages = $this->getPaymentWarrantCalculationWages($payment);

        $domicileWageExpenseType      = $this->expenseTypeRepository->findOneBy(['code' => ExpenseType::DOMICILE_WAGE]);
        $internationalWageExpenseType = $this->expenseTypeRepository->findOneBy(
            [
                'code' => ExpenseType::INTERNATIONAL_WAGE
            ]
        );

        foreach ($warrantCalculationWages as $wage) {
            $expenseType = $wage['country_domicile'] ? $domicileWageExpenseType : $internationalWageExpenseType;

            if (!$expenseType) {
                throw new RecordNotFoundException($expenseType);
            }

            $expense = [
                'warrant_code'      => $wage['warrant_code'],
                'employee_name'     => $wage['employee_name'],
                'employee_surname'  => $wage['employee_surname'],
                'department_name'   => $wage['department_name'],
                'expense_type_code' => $expenseType->getCode(),
                'expense_type_name' => $expenseType->getName(),
                'expense_amount'    => $wage['expense_amount'],
                'currency_code'     => $wage['currency_code'],
                'currency_name'     => $wage['currency_name']
            ];

            $expenses[] = $expense;
        }

        $advanceExpenseType = $this->expenseTypeRepository->findOneBy(
            [
                'code' => ExpenseType::ADVANCES
            ]
        );

        if (!$advanceExpenseType) {
            throw new RecordNotFoundException($advanceExpenseType);
        }

        foreach ($payment->getWarrantPayments() as $warrantPayment) {
            $warrant = $warrantPayment->getWarrant();

            if (!$warrant) {
                throw new RecordNotFoundException($warrant);
            }

            if ($warrant->isAdvancesRequired()) {
                if ($warrant->getStatus()->getCode() === WarrantStatus::CALCULATION_IN_PAYMENT) {
                    $expenseAmount = -$warrant->getAdvancesAmount();
                } else {
                    $expenseAmount = $warrant->getAdvancesAmount();
                }

                $expense = [
                    'warrant_code'      => $warrant->getCode(),
                    'employee_name'     => $warrant->getEmployee()->getName(),
                    'employee_surname'  => $warrant->getEmployee()->getSurname(),
                    'department_name'   => $warrant->getDepartment()->getName(),
                    'expense_type_code' => $advanceExpenseType->getCode(),
                    'expense_type_name' => $advanceExpenseType->getName(),
                    'expense_amount'    => $expenseAmount,
                    'currency_code'     => $warrant->getAdvancesCurrency()->getCode(),
                    'currency_name'     => $warrant->getAdvancesCurrency()->getName()
                ];

                $expenses[] = $expense;
            }
        }

        return $this->prepareExpensesForReport($expenses);
    }

    private function getPaymentWarrantCalculationWages(Payment $payment)
    {
        return $this->paymentRepository->findPaymentWarrantCalculationWages($payment->getId());
    }

    private function prepareExpensesForReport(array $expenses)
    {
        usort(
            $expenses,
            function ($a, $b) {
                $warrantComparison = strcmp($a['warrant_code'], $b['warrant_code']);
                if ($warrantComparison != 0) {
                    return $warrantComparison;
                }

                if ($a['expense_type_code'] === ExpenseType::ADVANCES && $b['expense_type_code'] !== ExpenseType::ADVANCES) {
                    return 1;
                }

                if ($a['expense_type_code'] !== ExpenseType::ADVANCES && $b['expense_type_code'] === ExpenseType::ADVANCES) {
                    return -1;
                }

                return 0;
            }
        );

        return $expenses;
    }
}