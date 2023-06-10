<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\PredefinedExpense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PredefinedExpense>
 *
 * @method PredefinedExpense|null find($id, $lockMode = null, $lockVersion = null)
 * @method PredefinedExpense|null findOneBy(array $criteria, array $orderBy = null)
 * @method PredefinedExpense[]    findAll()
 * @method PredefinedExpense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PredefinedExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PredefinedExpense::class);
    }

    public function save(PredefinedExpense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PredefinedExpense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
