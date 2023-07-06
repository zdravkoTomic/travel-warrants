<?php

namespace App\Repository;

use App\Entity\Warrant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Warrant>
 *
 * @method Warrant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warrant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warrant[]    findAll()
 * @method Warrant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warrant::class);
    }

    public function save(Warrant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Warrant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getNewWarrantOrdinalNumber()
    {
        $result = $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $result + 1;
    }
}
