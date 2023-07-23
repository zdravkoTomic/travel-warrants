<?php

namespace App\EventListener\Warrant;

use App\Entity\Warrant;
use App\Entity\WarrantStatusFlow;
use App\Service\Warrant\WarrantStatusFlowService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[AsEntityListener(event: Events::postUpdate, entity: Warrant::class)]
class UpdateWarrantStatusFlowListener
{
    private Security $security;
    private WarrantStatusFlowService $warrantStatusFlowService;

    public function __construct(Security $security, WarrantStatusFlowService $warrantStatusFlowService)
    {
        $this->security                 = $security;
        $this->warrantStatusFlowService = $warrantStatusFlowService;
    }

    public function postUpdate(Warrant $warrant, PostUpdateEventArgs $eventArgs): void
    {
        $em = $eventArgs->getObjectManager();

        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($warrant);

        if (!isset($changeSet['status'])) {
            return;
        }

        $newStatus = $warrant->getStatus();
        $user      = $this->security->getUser();

        if (!$newStatus) {
            throw new InvalidArgumentException('Expected valid warrant status');
        }

        if (!$user) {
            throw new AuthenticationException("Couldn't retrieve current user");
        }

        $warrantStatusFlow = $this->warrantStatusFlowService->setWarrantStatusFlow(
            $warrant,
            $newStatus,
            $user
        );

        $entityManager = $eventArgs->getObjectManager();
        $entityManager->persist($warrantStatusFlow);
        $entityManager->flush();
    }
}