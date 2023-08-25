<?php

namespace App\Controller\Payment;

use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Employee;
use App\Entity\Payment;
use App\Repository\Codebook\App\WarrantStatusRepository;
use App\Service\Payment\PaymentService;
use App\Service\Warrant\WarrantStatusService;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ClosePaymentAction extends AbstractController
{
    private PaymentService $paymentService;
    private WarrantStatusService $warrantStatusService;
    private WarrantStatusRepository $warrantStatusRepository;

    public function __construct(
        PaymentService          $paymentService,
        WarrantStatusService    $warrantStatusService,
        WarrantStatusRepository $warrantStatusRepository
    ) {
        $this->paymentService          = $paymentService;
        $this->warrantStatusService    = $warrantStatusService;
        $this->warrantStatusRepository = $warrantStatusRepository;
    }

    public function __invoke(Payment $payment, #[CurrentUser] Employee $user = null): JsonResponse|Response
    {
        try {
            if (!$user) {
                throw new RuntimeException("Couldn't retrieve current user");
            }

            $this->paymentService->closeOpenedPayment($payment, $user);

            $warrantStatusCalculationEdit = $this->warrantStatusRepository->findExistingByCode(
                WarrantStatus::CALCULATION_EDIT
            );

            $warrantStatusClosed = $this->warrantStatusRepository->findExistingByCode(
                WarrantStatus::CLOSED
            );

            foreach ($payment->getWarrantPayments() as $warrantPayment) {
                if ($warrantPayment->getWarrant()->getStatus()->getCode() === WarrantStatus::ADVANCE_IN_PAYMENT) {
                    $this->warrantStatusService->updateWarrantStatus(
                        $warrantPayment->getWarrant(),
                        $warrantStatusCalculationEdit
                    );
                }

                if ($warrantPayment->getWarrant()->getStatus()->getCode() === WarrantStatus::CALCULATION_IN_PAYMENT) {
                    $this->warrantStatusService->updateWarrantStatus(
                        $warrantPayment->getWarrant(),
                        $warrantStatusClosed
                    );
                }
            }

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}