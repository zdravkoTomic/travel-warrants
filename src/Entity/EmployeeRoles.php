<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Codebook\App\Role;
use App\Entity\Codebook\Department;
use App\Repository\EmployeeRolesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EmployeeRolesRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_employee_role']],
            security            : "is_granted('ROLE_ADMIN')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['get_employee_role']],
            security            : "is_granted('ROLE_ADMIN')"
        ),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'employee.surname',
    'employee.code',
    'employee.username',
    'department.name',
    'role.name' => 'ASC', 'ACTIVE'
])]
class EmployeeRoles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_employee_role', 'get_employee'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'employeeRoles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_employee_role'])]
    private ?Employee $employee = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_employee_role', 'get_employee'])]
    private ?Role $role = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_employee_role', 'get_employee'])]
    private ?Department $department = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }
}
