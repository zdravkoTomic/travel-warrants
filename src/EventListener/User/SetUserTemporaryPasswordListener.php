<?php

namespace App\EventListener\User;

use App\Entity\Employee;
use App\Service\User\PasswordHandlerService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::onFlush)]
class SetUserTemporaryPasswordListener
{
    private PasswordHandlerService $passwordHandlerService;

    public function __construct(PasswordHandlerService $passwordHandlerService)
    {
        $this->passwordHandlerService = $passwordHandlerService;
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Employee) {
                $password = $this->passwordHandlerService->generateUserTemporaryPassword($entity);
                $entity->setPassword($password);

                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);
                $uow->computeChangeSets();
            }
        }
    }
}