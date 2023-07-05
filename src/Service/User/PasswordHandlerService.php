<?php

namespace App\Service\User;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Service\Dto\UserPasswordDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordHandlerService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct (EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function resetUserPassword(Employee $user, UserPasswordDto $userPasswordDto): void
    {
        if ($user->getEmail() !== $userPasswordDto->email) {
            throw new AccessDeniedHttpException('Unauthorized access while reseting password');
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $userPasswordDto->password
        );

        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function generateUserTemporaryPassword(Employee $user): string
    {
        $tmpPlainPassword = random_bytes(10);

        return $this->passwordHasher->hashPassword(
            $user,
            $tmpPlainPassword
        );
    }
}