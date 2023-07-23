<?php

namespace App\Repository;

use App\Entity\WarrantStatusFlow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantStatusFlow>
 *
 * @method WarrantStatusFlow|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantStatusFlow|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantStatusFlow[]    findAll()
 * @method WarrantStatusFlow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantStatusFlowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantStatusFlow::class);
    }

    public function save(WarrantStatusFlow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantStatusFlow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
