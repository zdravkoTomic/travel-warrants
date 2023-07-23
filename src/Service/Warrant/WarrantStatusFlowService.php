<?php

namespace App\Service\Warrant;

use App\Entity\Codebook\App\WarrantStatus;
use App\Entity\Warrant;
use App\Entity\WarrantStatusFlow;
use App\Repository\EmployeeRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class WarrantStatusFlowService
{
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function setWarrantStatusFlow(
        Warrant       $warrant,
        WarrantStatus $warrantStatus,
        UserInterface $user
    ): WarrantStatusFlow {
        $employee = $this->employeeRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $warrantStatusFlow = new WarrantStatusFlow();
        $warrantStatusFlow->setWarrant($warrant)
            ->setWarrantStatus($warrantStatus)
            ->setCreatedBy($employee);

        return $warrantStatusFlow;
    }
}