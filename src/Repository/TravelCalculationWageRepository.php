<?php

namespace App\Repository;

use App\Entity\TravelCalculationWage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TravelCalculationWage>
 *
 * @method TravelCalculationWage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelCalculationWage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelCalculationWage[]    findAll()
 * @method TravelCalculationWage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelCalculationWageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravelCalculationWage::class);
    }

    public function save(TravelCalculationWage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TravelCalculationWage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
