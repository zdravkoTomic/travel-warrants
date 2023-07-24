<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\CountryWage;
use App\Exception\RecordNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CountryWage>
 *
 * @method CountryWage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountryWage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountryWage[]    findAll()
 * @method CountryWage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryWageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CountryWage::class);
    }

    public function save(CountryWage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CountryWage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function findActiveByCountryId(int $countryId)
    {
        $result = $this->createQueryBuilder('cw')
            ->where('cw.country = :countryId')
            ->andWhere('cw.active = 1')
            ->setParameter('countryId', $countryId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            throw new RecordNotFoundException($this->getClassName());
        }

        return $result;
    }

    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function getDomicileWageCurrency(): CountryWage
    {
        $result = $this->createQueryBuilder('cw')
            ->innerJoin('cw.country', 'c')
            ->where('c.domicile = 1')
            ->andWhere('cw.active = 1')
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            throw new RecordNotFoundException($this->getClassName());
        }

        return $result;
    }
}
