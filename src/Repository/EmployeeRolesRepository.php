<?php

namespace App\Repository;

use App\Entity\EmployeeRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeeRoles>
 *
 * @method EmployeeRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeRoles[]    findAll()
 * @method EmployeeRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeRoles::class);
    }

    public function save(EmployeeRoles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmployeeRoles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
