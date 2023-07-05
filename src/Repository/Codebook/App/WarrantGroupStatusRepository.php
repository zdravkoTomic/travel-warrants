<?php

namespace App\Repository\Codebook\App;

use App\Entity\Codebook\App\WarrantGroupStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantGroupStatus>
 *
 * @method WarrantGroupStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantGroupStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantGroupStatus[]    findAll()
 * @method WarrantGroupStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantGroupStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantGroupStatus::class);
    }

    public function save(WarrantGroupStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantGroupStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
