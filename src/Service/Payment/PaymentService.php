<?php

namespace App\Service\Payment;

use App\Entity\Codebook\WarrantPaymentStatus;
use App\Entity\Employee;
use App\Entity\Payment;
use App\Repository\Codebook\App\WarrantStatusRepository;
use App\Repository\Codebook\WarrantPaymentStatusRepository;
use App\Repository\PaymentRepository;
use App\Repository\WarrantRepository;
use RuntimeException;

class PaymentService
{
    private PaymentRepository $paymentRepository;
    private WarrantPaymentStatusRepository $warrantPaymentStatusRepository;
    private WarrantRepository $warrantRepository;
    private WarrantStatusRepository $warrantStatusRepository;

    public function __construct(
        PaymentRepository              $paymentRepository,
        WarrantPaymentStatusRepository $warrantPaymentStatusRepository,
        WarrantRepository              $warrantRepository,
        WarrantStatusRepository        $warrantStatusRepository
    ) {
        $this->paymentRepository              = $paymentRepository;
        $this->warrantPaymentStatusRepository = $warrantPaymentStatusRepository;
        $this->warrantRepository              = $warrantRepository;
        $this->warrantStatusRepository        = $warrantStatusRepository;
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

        $this->paymentRepository->closePayment($payment, $user, $closedWarrantPaymentStatus);
    }
}