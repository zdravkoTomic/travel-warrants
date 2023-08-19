<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\WarrantPaymentStatus;
use App\Entity\Warrant;
use App\Entity\WarrantPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantPaymentStatus>
 *
 * @method WarrantPaymentStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantPaymentStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantPaymentStatus[]    findAll()
 * @method WarrantPaymentStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantPaymentStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantPaymentStatus::class);
    }

    public function save(WarrantPaymentStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantPaymentStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
