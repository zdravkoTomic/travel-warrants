<?php

namespace App\EventListener\Warrant;

use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use App\Service\Warrant\WarrantStatusService;
use App\Service\WarrantPayment\WarrantPaymentService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;

#[AsEntityListener(event: Events::preUpdate, entity: Warrant::class)]
class HandleWarrantStatusChangeListener
{
    private WarrantStatusService $warrantStatusService;
    private WarrantPaymentService $warrantPaymentService;

    public function __construct(
        WarrantStatusService  $warrantStatusService,
        WarrantPaymentService $warrantPaymentService
    ) {
        $this->warrantStatusService  = $warrantStatusService;
        $this->warrantPaymentService = $warrantPaymentService;
    }

    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function preUpdate(Warrant $warrant, PreUpdateEventArgs $eventArgs): void
    {
        $em = $eventArgs->getObjectManager();

        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($warrant);

        if (!isset($changeSet['status'])) {
            return;
        }

        $oldStatus = $eventArgs->getOldValue('status');
        $newStatus = $warrant->getStatus();

        if (!$newStatus) {
            throw new RecordNotFoundException($warrant->getStatus());
        }

        $newStatusCodeTransition = 'to_' . $newStatus->getCode();

        $warrant->setStatus($oldStatus);

        $this->warrantStatusService->validateAndSetStatusChange($warrant, $newStatusCodeTransition);
        $this->warrantStatusService->setWarrantGroupStatus($warrant);

        if ($newStatus->getCode() === WarrantStatus::ADVANCE_IN_PAYMENT
            || $newStatus->getCode() === WarrantStatus::CALCULATION_IN_PAYMENT
        ) {
            $this->warrantPaymentService->addWarrantToWarrantPayment($warrant, $newStatus);
        }

        if ($oldStatus->getCode() === WarrantStatus::ADVANCE_IN_PAYMENT
            || $oldStatus->getCode() === WarrantStatus::CALCULATION_IN_PAYMENT
        ) {
            $this->warrantPaymentService->removeWarrantFromWarrantPayment($warrant);
        }
    }
}