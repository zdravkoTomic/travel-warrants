<?php

namespace App\EventListener\Warrant;

use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;

#[AsEntityListener(event: Events::preUpdate, entity: Warrant::class)]
class SetAdvancesIndicatorListener
{
    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function preUpdate(Warrant $warrant, PreUpdateEventArgs $eventArgs): void
    {
        $em = $eventArgs->getObjectManager();

        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($warrant);

        if (!isset($changeSet['advancesAmount'])) {
            return;
        }

        $oldAdvancesAmount = $eventArgs->getOldValue('advancesAmount');
        $newAdvancesAmount = $warrant->getAdvancesAmount();

        if ($oldAdvancesAmount === 0 && $newAdvancesAmount > 0) {
            $warrant->setAdvancesRequired(true);
        }

        if ($oldAdvancesAmount > 0 && $newAdvancesAmount === 0) {
            $warrant->setAdvancesRequired(false);
        }
    }
}