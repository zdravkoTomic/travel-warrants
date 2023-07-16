<?php

namespace App\Service\User;

use App\ApiResource\Dto\UserPasswordDto;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        $user->setPassword($hashedPassword)
            ->setFullyAuthorized(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function getTemporaryPlainTextPassword(): string
    {
        return bin2hex(random_bytes(10));
    }

    public function generateUserTemporaryHashedPassword(Employee $user, string $tmpPlainPassword): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            $tmpPlainPassword
        );
    }
}