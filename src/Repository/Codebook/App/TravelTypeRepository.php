<?php

namespace App\Repository\Codebook\App;

use App\Entity\Codebook\App\TravelType;
use App\Exception\RecordNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TravelType>
 *
 * @method TravelType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelType[]    findAll()
 * @method TravelType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravelType::class);
    }

    public function save(TravelType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TravelType $entity, bool $flush = false): void
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
    public function findExistingByCode(string $code)
    {
        $result = $this->createQueryBuilder('tt')
            ->where('tt.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            throw new RecordNotFoundException($this->getClassName());
        }

        return $result;
    }
}
