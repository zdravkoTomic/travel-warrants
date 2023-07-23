<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiResource\Dto\UserPasswordDto;
use App\Controller\Security\ChangePasswordProcessor;
use App\Controller\Security\LoginController;
use App\Controller\Security\LogoutController;
use App\Entity\Codebook\Department;
use App\Entity\Codebook\WorkPosition;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_employee']],
            security            : "is_granted('ROLE_EMPLOYEE')",
            name                : "get_employee"
        ),
        new GetCollection(
            uriTemplate         : '/catalog/employees',
            paginationEnabled   : false,
            normalizationContext: ['groups' => ['get_catalog_employee']],
            security            : "is_granted('ROLE_EMPLOYEE')"
        ),
        new GetCollection(
            paginationEnabled           : true,
            paginationClientItemsPerPage: true,
            normalizationContext        : ['groups' => ['get_employee']],
            security                    : "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['post_employee']],
            security              : "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate           : '/login',
            controller            : LoginController::class,
            description           : 'Log in employee',
            denormalizationContext: ['groups' => ['login_employee']],
            name                  : 'app_login'
        ),
        new Post(
            uriTemplate           : '/logout',
            controller            : LogoutController::class,
            description           : 'Logout em ployee',
            denormalizationContext: ['groups' => ['logout_employee']],
            name                  : 'app_logout'
        ),
        new Put(
            denormalizationContext: ['groups' => ['put_employee']],
            security              : "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            uriTemplate: '/employees/{id}/change_password',
            input      : UserPasswordDto::class,
            processor  : ChangePasswordProcessor::class
        )
    ]
)]
#[UniqueEntity(
    fields   : ['code'],
    message  : 'This code is already in use on an employee.',
    errorPath: 'code',
)]
#[UniqueEntity(
    fields   : ['username'],
    message  : 'This username is already in use on an employee.',
    errorPath: 'username',
)]
#[UniqueEntity(
    fields   : ['email'],
    message  : 'This email is already in use on an employee.',
    errorPath: 'email',
)]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const DEFAULT_USER_ROLE = 'ROLE_EMPLOYEE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_department',
        'get_catalog_employee',
        'put_employee',
        'get_employee_role'
    ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_employee_role',
        'put_employee'
    ])]
    private ?Department $department = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_warrant',
        'get_department',
        'get_employee_role',
        'put_employee'
    ])]
    private ?WorkPosition $workPosition = null;

    #[ORM\Column(length: 20, nullable: false)]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_user_warrants_by_status',
        'get_catalog_employee',
        'get_employee_role',
        'put_employee'
    ])]
    private ?string $code = null;

    #[ORM\Column(length: 100, nullable: false)]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_department',
        'get_catalog_employee',
        'put_employee',
        'get_employee_role'
    ])]
    private string $name;

    #[ORM\Column(length: 100, nullable: false)]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_warrant',
        'get_user_warrants_by_status',
        'get_department',
        'get_catalog_employee',
        'put_employee',
        'get_employee_role'
    ])]
    private ?string $surname = null;

    #[ORM\Column(length: 100, nullable: false)]
    #[Groups([
        'get_employee',
        'post_employee',
        'get_department',
        'get_catalog_employee',
        'put_employee',
        'get_employee_role'
    ])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get_employee', 'post_employee', 'login_employee', 'get_department', 'put_employee'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['login_employee'])]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['get_employee', 'post_employee', 'put_employee'])]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column]
    #[Groups(['get_employee', 'post_employee', 'put_employee', 'get_employee_role'])]
    private ?bool $active = true;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: EmployeeRoles::class)]
    private Collection $employeeRoles;

    #[ORM\Column]
    #[Groups(['get_employee'])]
    private ?bool $fullyAuthorized = false;

    #[Groups(['get_employee'])]
    private ?array $roles;

    public function __construct()
    {
        $this->employeeRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWorkPosition(): ?WorkPosition
    {
        return $this->workPosition;
    }

    public function setWorkPosition(?WorkPosition $workPosition): static
    {
        $this->workPosition = $workPosition;

        return $this;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function addEmployeeRole(EmployeeRoles $employeeRole): static
    {
        if (!$this->employeeRoles->contains($employeeRole)) {
            $this->employeeRoles->add($employeeRole);
            $employeeRole->setEmployee($this);
        }

        return $this;
    }

    public function removeEmployeeRole(EmployeeRoles $employeeRole): static
    {
        if ($this->employeeRoles->removeElement($employeeRole)) {
            // set the owning side to null (unless already changed)
            if ($employeeRole->getEmployee() === $this) {
                $employeeRole->setEmployee(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = [];

        foreach ($this->getEmployeeRoles() as $employeeRole) {
            if ($employeeRole->getRole()) {
                $roles[] = $employeeRole->getRole()->getName();
            }
        }

        $roles[] = self::DEFAULT_USER_ROLE;

        return $roles;
    }

    /**
     * @return Collection<int, EmployeeRoles>
     */
    public function getEmployeeRoles(): Collection
    {
        return $this->employeeRoles;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return bool|null
     */
    public function isFullyAuthorized(): ?bool
    {
        return $this->fullyAuthorized;
    }

    /**
     * @param bool|null $fullyAuthorized
     */
    public function setFullyAuthorized(?bool $fullyAuthorized): void
    {
        $this->fullyAuthorized = $fullyAuthorized;
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

}
