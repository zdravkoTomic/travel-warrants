<?php

namespace App\EventListener\Warrant;

use App\Entity\Warrant;
use App\Exception\RecordNotFoundException;
use App\Service\Warrant\WarrantInitialDataService;
use App\Service\Warrant\WarrantStatusFlowService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::prePersist)]
class SetWarrantInitialDataListener
{
    private WarrantInitialDataService $warrantInitialDataService;

    private WarrantStatusFlowService $warrantStatusFlowService;

    private Security $security;

    public function __construct(
        WarrantInitialDataService $warrantInitialDataService,
        WarrantStatusFlowService  $warrantStatusFlowService,
        Security                  $security
    ) {
        $this->warrantInitialDataService = $warrantInitialDataService;
        $this->warrantStatusFlowService  = $warrantStatusFlowService;
        $this->security                  = $security;
    }

    /**
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $warrant = $args->getObject();

        if (!$warrant instanceof Warrant || $warrant->getId()) {
            return;
        }

        $this->warrantInitialDataService->setInitialData($warrant);

        $user = $this->security->getUser();

        if (!$user) {
            throw new Exception("Couldn't retrieve current user");
        }

        $warrantStatusFlow = $this->warrantStatusFlowService->setWarrantStatusFlow(
            $warrant,
            $warrant->getStatus(),
            $user
        );

        $warrant->addWarrantStatusFlow($warrantStatusFlow);
    }
}