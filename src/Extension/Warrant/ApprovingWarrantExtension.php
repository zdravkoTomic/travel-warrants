<?php

namespace App\Extension\Warrant;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Codebook\App\Role;
use App\Entity\Warrant;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class ApprovingWarrantExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private readonly Security $security, private readonly RequestStack $requestStack)
    {
    }
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $routeName = $currentRequest?->get('_route');

        if (Warrant::class !== $resourceClass
            || $routeName !== 'get_approving_warrants'
            || !$this->security->getUser()
            || $this->security->isGranted('ROLE_ADMIN')
        ) {
            return;
        }

        $departments = [];
        $employeeRoles = $this->security->getUser()->getEmployeeRoles();

        foreach ($employeeRoles as $employeeRole) {
            if ($employeeRole->getRole()->getName() === Role::ROLE_APPROVER) {
                $department = $employeeRole->getDepartment();
                $departments[] = $department->getCode();
            }
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder
            ->innerJoin(sprintf('%s.department', $rootAlias), 'department')
            ->andWhere($queryBuilder->expr()->in('department.code', ':departments'))
            ->setParameter('departments', $departments);
    }
}