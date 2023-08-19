<?php

namespace App\Repository;

use App\Entity\Codebook\WarrantPaymentStatus;
use App\Entity\Employee;
use App\Entity\Payment;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 *
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function save(Payment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Payment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getOpenedPayment(): ?Payment
    {
        $result = $this->createQueryBuilder('p')
            ->innerJoin('p.warrantPaymentStatus', 'wps')
            ->where('wps.code = :warrantPaymentStatusCode')
            ->setParameter('warrantPaymentStatusCode', WarrantPaymentStatus::OPENED)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function closePayment(
        Payment              $payment,
        Employee             $user,
        WarrantPaymentStatus $warrantPaymentStatusClosed
    ): void {
        $currentDateTime = new DateTime('now');

        $this->createQueryBuilder('p')
            ->update()
            ->innerJoin('p.warrantPaymentStatus', 'wps')
            ->set('p.warrantPaymentStatus', ':closedStatus')
            ->set('p.closedBy', ':userId')
            ->set('p.closedAt', ':currentDate')
            ->where('p.id = :paymentId')
            ->setParameter('closedStatus', $warrantPaymentStatusClosed->getId())
            ->setParameter('userId', $user->getId())
            ->setParameter('currentDate', $currentDateTime)
            ->setParameter('paymentId', $payment->getId())
            ->getQuery()
            ->execute();
    }
}
