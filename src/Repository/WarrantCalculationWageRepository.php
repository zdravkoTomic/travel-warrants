<?php

namespace App\Repository;

use App\Entity\WarrantCalculationWage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantCalculationWage>
 *
 * @method WarrantCalculationWage|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantCalculationWage|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantCalculationWage[]    findAll()
 * @method WarrantCalculationWage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantCalculationWageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantCalculationWage::class);
    }

    public function save(WarrantCalculationWage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantCalculationWage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
