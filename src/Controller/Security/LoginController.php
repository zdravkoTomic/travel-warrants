<?php

namespace App\Controller\Security;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
class LoginController extends AbstractController
{
    public function __invoke(IriConverterInterface $iriConverter, #[CurrentUser] Employee $user = null): Response
    {
        if (!$user) {
            return $this->json([
                'error' => 'Invalid login request'
            ], 401);
        }

        return new Response(
            null,
            204,
            ['Location' => $iriConverter->getIriFromResource($user)]
        );
    }
}