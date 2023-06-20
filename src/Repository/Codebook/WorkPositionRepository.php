<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\WorkPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkPosition>
 *
 * @method WorkPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkPosition[]    findAll()
 * @method WorkPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkPosition::class);
    }

    public function save(WorkPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
