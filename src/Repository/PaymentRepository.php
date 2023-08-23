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

    public function findPaymentExpensesById(int $paymentId): ?array
    {
        return $this->createQueryBuilder('p')
            ->select(
                'w.code AS warrant_code',
                'e.name AS employee_name',
                'e.surname AS employee_surname',
                'd.name AS department_name',
                'et.code AS expense_type_code',
                'et.name AS expense_type_name',
                'wce.amount AS expense_amount',
                'c.code AS currency_code',
                'c.name AS currency_name'
            )
            ->innerJoin('p.warrantPayments', 'wp')
            ->innerJoin('wp.warrant', 'w')
            ->innerJoin('w.warrantCalculation', 'wc')
            ->innerJoin('wc.warrantCalculationExpenses', 'wce')
            ->innerJoin('wce.expenseType', 'et')
            ->innerJoin('wce.currency', 'c')
            ->innerJoin('w.employee', 'e')
            ->innerJoin('w.department', 'd')
            ->where('p.id = :paymentId')
            ->setParameter('paymentId', $paymentId)
            ->getQuery()
            ->getResult();
    }

    public function findPaymentWarrantCalculationWages(int $paymentId)
    {
        return $this->createQueryBuilder('p')
            ->select(
                'w.code AS warrant_code',
                'e.name AS employee_name',
                'e.surname AS employee_surname',
                'd.name AS department_name',
                'co.code AS country_code',
                'co.domicile AS country_domicile',
                'wcw.amount AS expense_amount',
                'c.code AS currency_code',
                'c.name AS currency_name'
            )
            ->innerJoin('p.warrantPayments', 'wp')
            ->innerJoin('wp.warrant', 'w')
            ->innerJoin('w.warrantCalculation', 'wc')
            ->innerJoin('wc.warrantCalculationWages', 'wcw')
            ->innerJoin('wcw.country', 'co')
            ->innerJoin('wcw.currency', 'c')
            ->innerJoin('w.employee', 'e')
            ->innerJoin('w.department', 'd')
            ->where('p.id = :paymentId')
            ->setParameter('paymentId', $paymentId)
            ->getQuery()
            ->getResult();
    }
}
