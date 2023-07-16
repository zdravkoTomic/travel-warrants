<?php

namespace App\Controller\Security;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class LoginController extends AbstractController
{
    /**
     * @throws \JsonException
     */
    public function __invoke(SerializerInterface $serializer, #[CurrentUser] Employee $user = null)
    {
        if (!$user) {
            new JsonResponse(['error' => 'Invalid email or password'], 401);
        }

        $cookie = new Cookie('user_auth', bin2hex(random_bytes(10)), time() + 3600, '/', "localhost");

        $userData = $serializer->normalize($user, null, [AbstractNormalizer::ATTRIBUTES => ['id', 'username', 'email']]);

        $response = new JsonResponse(['user' => $userData], 201);
        $response->headers->setCookie($cookie);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}