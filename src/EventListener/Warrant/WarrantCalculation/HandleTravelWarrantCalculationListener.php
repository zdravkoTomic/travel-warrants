<?php

namespace App\EventListener\Warrant\WarrantCalculation;

use App\Entity\Codebook\VehicleType;
use App\Entity\WarrantCalculation;
use App\Service\Warrant\WarrantCalculation\WarrantCalculationService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Exception;

#[AsDoctrineListener(event: Events::onFlush)]
class HandleTravelWarrantCalculationListener
{
    private WarrantCalculationService $warrantCalculationService;

    public function __construct(WarrantCalculationService $warrantCalculationService)
    {
        $this->warrantCalculationService = $warrantCalculationService;
    }

    /**
     * @throws Exception
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em  = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof WarrantCalculation) {
                $this->warrantCalculationService->setTravelDuration($entity);

                $this->warrantCalculationService->setWarrantCalculationWage($entity);

                $this->warrantCalculationService->setWarrantCalculationVehicleExpense($entity);

                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);

                foreach ($entity->getWarrantCalculationWages() as $wage) {
                    if (!$uow->isScheduledForInsert($wage)) {
                        $uow->scheduleForInsert($wage);
                        $uow->computeChangeSet($em->getClassMetadata(get_class($wage)), $wage);
                    }
                }

                foreach ($entity->getWarrantCalculationExpenses() as $expense) {
                    if (!$uow->isScheduledForInsert($expense)) {
                        $uow->scheduleForInsert($expense);
                        $uow->computeChangeSet($em->getClassMetadata(get_class($expense)), $expense);
                    } else {
                        $this->warrantCalculationService->setExpenseAmountAndCurrency($expense);
                        $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($expense)), $expense);
                    }
                }
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof WarrantCalculation) {
                $changeSet = $uow->getEntityChangeSet($entity);

                $this->warrantCalculationService->setTravelDuration($entity);

                foreach ($entity->getWarrantCalculationWages() as $wage) {
                    $entity->removeWarrantCalculationWage($wage);
                    $em->remove($wage);
                }

                foreach ($entity->getWarrantCalculationExpenses() as $expense) {
                    if ($expense->getExpenseType()->getCode() === VehicleType::PERSONAL_VEHICLE
                        || $expense->getExpenseType()->getCode() === VehicleType::OFFICAL_PERSONAL_VEHICLE
                    ) {
                        $entity->removeWarrantCalculationExpense($expense);
                        $em->remove($expense);
                    }
                }

                $this->warrantCalculationService->setWarrantCalculationWage($entity);

                $this->warrantCalculationService->setWarrantCalculationVehicleExpense($entity);

                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);

                foreach ($entity->getWarrantCalculationWages() as $wage) {
                    if (!$uow->isScheduledForInsert($wage)) {
                        $uow->scheduleForInsert($wage);
                        $uow->computeChangeSet($em->getClassMetadata(get_class($wage)), $wage);
                    }
                }

                foreach ($entity->getWarrantCalculationExpenses() as $expense) {
                    if (!$uow->isScheduledForInsert($expense)) {
                        $uow->scheduleForInsert($expense);
                        $uow->computeChangeSet($em->getClassMetadata(get_class($expense)), $expense);
                    } else {
                        $this->warrantCalculationService->setExpenseAmountAndCurrency($expense);
                        $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($expense)), $expense);
                    }
                }
            }
        }
    }
}