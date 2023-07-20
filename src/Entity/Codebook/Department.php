<?php

namespace App\Entity\Codebook;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Employee;
use App\Repository\Codebook\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_department']],
            security            : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(
            uriTemplate      : '/catalog/departments',
            paginationEnabled: false,
            security         : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            normalizationContext        : ['groups' => ['get_department']],
            security                    : "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['post_department']],
            security              : "is_granted('ROLE_ADMIN')",
        ),
        new Put(
            denormalizationContext: ['groups' => ['put_department']],
            security              : "is_granted('ROLE_ADMIN')"
        )
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['code', 'name', 'active', 'parent.name' => 'ASC', 'ACTIVE'])]
#[UniqueEntity(
    fields   : ['code', 'active'],
    message  : 'This code is already in use on an active record.',
    errorPath: 'code',
)]
#[ApiFilter(BooleanFilter::class, properties: [
    'active'
])]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'get_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_department',
        'post_department',
        'put_department',
        'get_employee_role'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups([
        'get_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_department',
        'post_department',
        'put_department',
        'get_employee_role'
    ])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'get_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_department',
        'post_department',
        'put_department',
        'get_employee_role'
    ])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_department', 'post_department', 'put_department'])]
    private ?bool $active = null;

    #[OneToMany(mappedBy: 'parent', targetEntity: Department::class)]
    private Collection $children;

    #[ManyToOne(targetEntity: Department::class, inversedBy: 'children')]
    #[JoinColumn(name: 'parent', referencedColumnName: 'id', nullable: true)]
    #[Groups(['get_department', 'post_department', 'put_department'])]
    private Department|null $parent = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Employee::class)]
    #[Groups(['get_department'])]
    private Collection $employees;

    public function __construct()
    {
        $this->children  = new ArrayCollection();
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    public function getParent(): ?Department
    {
        return $this->parent;
    }

    public function setParent(?Department $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setDepartment($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getDepartment() === $this) {
                $employee->setDepartment(null);
            }
        }

        return $this;
    }
}
