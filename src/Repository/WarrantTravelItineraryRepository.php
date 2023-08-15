<?php

namespace App\Repository;

use App\Entity\WarrantTravelItinerary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarrantTravelItinerary>
 *
 * @method WarrantTravelItinerary|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarrantTravelItinerary|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarrantTravelItinerary[]    findAll()
 * @method WarrantTravelItinerary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantTravelItineraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarrantTravelItinerary::class);
    }

    public function save(WarrantTravelItinerary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarrantTravelItinerary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
