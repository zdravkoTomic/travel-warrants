<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Warrant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
    private TokenStorageInterface $tokenStorage;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Warrant::class);
        $this->tokenStorage = $tokenStorage;
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
            ->select('max(w.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $result + 1;
    }

    /**
     * @throws QueryException
     */
    public function getWarrantsForUserByGroupStatus(
        $employeeId,
        $groupStatusId,
        int $page = 1,
        int $itemsPerPage = 30
    ): Paginator {
        $firstResult = ($page - 1) * $itemsPerPage;

        $queryBuilder = $this->createQueryBuilder('w')
            ->where('w.employee = :employeeId')
            ->andWhere('w.groupStatus = :groupStatusId')
            ->setParameter('employeeId', $employeeId)
            ->setParameter('groupStatusId', $groupStatusId);

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults($itemsPerPage);

        $queryBuilder->addCriteria($criteria);
        $doctrinePaginator = new DoctrinePaginator($queryBuilder);

        return new Paginator($doctrinePaginator);
    }

    /**
     * @throws QueryException
     */
    public function getWarrantsByStatus(int $statusId, int $page = 1, int $itemsPerPage = 30): Paginator
    {
        $firstResult = ($page - 1) * $itemsPerPage;

        $queryBuilder = $this->createQueryBuilder('w')
            ->where('w.status = :statusId')
            ->setParameter('statusId', $statusId);

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults($itemsPerPage);

        $queryBuilder->addCriteria($criteria);
        $doctrinePaginator = new DoctrinePaginator($queryBuilder);

        return new Paginator($doctrinePaginator);
    }

    public function updateWarrantStatus(Warrant $warrant, WarrantStatus $warrantStatus): void
    {
        $warrant->setStatus($warrantStatus);
        $this->getEntityManager()->persist($warrant);
        $this->getEntityManager()->flush();
    }
}
