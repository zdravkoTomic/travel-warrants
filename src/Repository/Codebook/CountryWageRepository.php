<?php

namespace App\Repository\Codebook;

use App\Entity\Codebook\CountryWage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
