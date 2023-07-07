<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class LogoutController extends AbstractController
{
    /**
     * @throws \Exception
     */
    public function __invoke(): Response
    {
        throw new \Exception('Logout configuration is wrongfully set');
    }
}