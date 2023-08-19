<?php

namespace App\Repository;

use App\Entity\Codebook\WarrantPaymentStatus;
use App\Entity\Warrant;
use App\Entity\WarrantPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantPayment>
 *
 * @method WarrantPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantPayment[]    findAll()
 * @method WarrantPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantPayment::class);
    }

    public function save(WarrantPayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantPayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeOpenedWarrantPaymentByWarrant(Warrant $warrant): void
    {
        $result = $this->createQueryBuilder( 'wp')
            ->join('wp.payment', 'p')
            ->join('p.warrantPaymentStatus', 'wps')
            ->where('wps.code = :status')
            ->andWhere('wp.warrant = :warrant')
            ->setParameter('status', WarrantPaymentStatus::OPENED)
            ->setParameter('warrant', $warrant)
            ->getQuery()
            ->getResult();

        foreach ($result as $warrantPayment) {
            $this->remove($warrantPayment);
        }
    }
}
