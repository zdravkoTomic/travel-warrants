<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\VehicleType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleType>
 *
 * @method VehicleType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleType[]    findAll()
 * @method VehicleType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleType::class);
    }

    public function save(VehicleType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VehicleType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
