<?php

namespace App\Repository\Codebook\App;

use App\Entity\Codebook\App\WarrantStatus;
use App\Exception\RecordNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantStatus>
 *
 * @method WarrantStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantStatus[]    findAll()
 * @method WarrantStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantStatus::class);
    }

    public function save(WarrantStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findExistingByCode(string $code)
    {
        $result = $this->createQueryBuilder('ws')
            ->where('ws.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            throw new RecordNotFoundException($this->getClassName());
        }

        return $result;
    }
}
