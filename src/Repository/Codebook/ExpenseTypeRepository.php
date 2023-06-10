<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\ExpenseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpenseType>
 *
 * @method ExpenseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseType[]    findAll()
 * @method ExpenseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseType::class);
    }

    public function save(ExpenseType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExpenseType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
