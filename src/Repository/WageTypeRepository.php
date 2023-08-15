<?php

namespace App\Repository;

use App\Entity\WageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WageType>
 *
 * @method WageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method WageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method WageType[]    findAll()
 * @method WageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WageTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WageType::class);
    }

    public function save(WageType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WageType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
