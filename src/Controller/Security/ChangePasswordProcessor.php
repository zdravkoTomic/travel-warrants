<?php

namespace App\Controller\Security;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Employee;
use App\Service\Dto\UserPasswordDto;
use App\Service\User\PasswordHandlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ChangePasswordProcessor extends AbstractController implements ProcessorInterface
{
    private PasswordHandlerService $passwordHandlerService;

    public function __construct(PasswordHandlerService $passwordHandlerService)
    {
        $this->passwordHandlerService = $passwordHandlerService;
    }

    /**
     * @param UserPasswordDto $data
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var Employee $user */
        $user = $this->getUser();

        $this->passwordHandlerService->resetUserPassword($user, $data);
    }
}