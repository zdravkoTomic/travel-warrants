<?php

namespace App\Service\WarrantPayment;

use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Codebook\WarrantPaymentStatus;
use App\Entity\Payment;
use App\Entity\PaymentType;
use App\Entity\Warrant;
use App\Entity\WarrantPayment;
use App\Repository\Codebook\WarrantPaymentStatusRepository;
use App\Repository\PaymentRepository;
use App\Repository\PaymentTypeRepository;
use App\Repository\WarrantPaymentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class WarrantPaymentService
{
    private EntityManagerInterface $entityManager;

    private PaymentRepository $paymentRepository;

    private WarrantPaymentStatusRepository $paymentStatusRepository;

    private WarrantPaymentRepository $warrantPaymentRepository;
    private PaymentTypeRepository $paymentTypeRepository;

    public function __construct(
        EntityManagerInterface         $entityManager,
        PaymentRepository              $paymentRepository,
        WarrantPaymentStatusRepository $paymentStatusRepository,
        WarrantPaymentRepository       $warrantPaymentRepository,
        PaymentTypeRepository          $paymentTypeRepository
    ) {
        $this->entityManager            = $entityManager;
        $this->paymentRepository        = $paymentRepository;
        $this->paymentStatusRepository  = $paymentStatusRepository;
        $this->warrantPaymentRepository = $warrantPaymentRepository;
        $this->paymentTypeRepository    = $paymentTypeRepository;
    }

    public function addWarrantToWarrantPayment(Warrant $warrant, WarrantStatus $warrantStatus)
    {
        $warrantPayment = new WarrantPayment();

        $paymentTypeAdvance     = $this->paymentTypeRepository->findOneBy(['code' => PaymentType::ADVANCE]);
        $paymentTypeCalculation = $this->paymentTypeRepository->findOneBy(['code' => PaymentType::CALCULATION]);

        $paymentType = $warrantStatus->getCode() === WarrantStatus::ADVANCE_IN_PAYMENT
            ? $paymentTypeAdvance
            : $paymentTypeCalculation;

        $warrantPayment->setPayment($this->getOpenedPayment())
            ->setWarrant($warrant)
            ->setPaymentType($paymentType);

        $this->entityManager->persist($warrantPayment);
    }

    private function getOpenedPayment(): Payment
    {
        $openedPayment = $this->paymentRepository->getOpenedPayment();

        if (!$openedPayment) {
            $openedWarrantPaymentStatus = $this->paymentStatusRepository->findOneBy(
                [
                    'code' => WarrantPaymentStatus::OPENED
                ]
            );

            $payment = new Payment();

            $payment->setWarrantPaymentStatus($openedWarrantPaymentStatus)
                ->setCreatedAt(new DateTime('now'));

            $this->entityManager->persist($payment);

            return $payment;
        }

        return $openedPayment;
    }

    public function removeWarrantFromWarrantPayment(Warrant $warrant): void
    {
        $this->warrantPaymentRepository->removeOpenedWarrantPaymentByWarrant($warrant);
    }
}