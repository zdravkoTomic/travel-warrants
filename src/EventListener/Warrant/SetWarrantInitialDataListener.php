<?php

namespace App\EventListener\Warrant;

use App\Entity\Warrant;
use App\Service\Warrant\WarrantInitialDataService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::prePersist)]
class SetWarrantInitialDataListener
{
    private WarrantInitialDataService $warrantInitialDataService;

    public function __construct(WarrantInitialDataService $warrantInitialDataService)
    {
        $this->warrantInitialDataService = $warrantInitialDataService;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $warrant = $args->getObject();

        if (!$warrant instanceof Warrant || $warrant->getId()) {
            return;
        }

        $this->warrantInitialDataService->setInitialData($warrant);
    }
}