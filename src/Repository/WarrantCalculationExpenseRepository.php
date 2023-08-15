<?php

namespace App\Repository;

use App\Entity\WarrantCalculationExpense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantCalculationExpense>
 *
 * @method WarrantCalculationExpense|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantCalculationExpense|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantCalculationExpense[]    findAll()
 * @method WarrantCalculationExpense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantCalculationExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantCalculationExpense::class);
    }

    public function save(WarrantCalculationExpense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantCalculationExpense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
