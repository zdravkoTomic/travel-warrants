<?php

namespace App\Repository;

use App\Entity\WarrantCalculation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantCalculation>
 *
 * @method WarrantCalculation|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantCalculation|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantCalculation[]    findAll()
 * @method WarrantCalculation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantCalculationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantCalculation::class);
    }

    public function save(WarrantCalculation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantCalculation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
