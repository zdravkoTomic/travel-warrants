<?php

namespace App\EventListener\Warrant;

use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use App\Service\Warrant\WarrantStatusService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;

#[AsEntityListener(event: Events::preUpdate, entity: Warrant::class)]
class HandleWarrantStatusChangeListener
{
    private WarrantStatusService $warrantStatusService;

    public function __construct(WarrantStatusService $warrantStatusService)
    {
        $this->warrantStatusService = $warrantStatusService;
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

        if (!$warrant->getStatus()) {
            throw new RecordNotFoundException($warrant->getStatus());
        }

        $newStatusCodeTransition = 'to_' . $warrant->getStatus()->getCode();

        $warrant->setStatus($oldStatus);

        $this->warrantStatusService->validateAndSetStatusChange($warrant, $newStatusCodeTransition);
        $this->warrantStatusService->setWarrantGroupStatus($warrant);
    }
}