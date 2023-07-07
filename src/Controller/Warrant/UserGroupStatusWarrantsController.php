<?php

namespace App\Controller\Warrant;

use App\Repository\WarrantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UserGroupStatusWarrantsController extends AbstractController
{
    private WarrantRepository $warrantRepository;
    private SerializerInterface $serializer;

    public function __construct(WarrantRepository $warrantRepository, SerializerInterface $serializer)
    {
        $this->warrantRepository = $warrantRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(int $employeeId, int $groupStatusId): JsonResponse
    {
        $data = $this->warrantRepository->findBy(
            [
                'employee' => $employeeId,
                'groupStatus' => $groupStatusId
            ]
        );

        $serializedData = $this->serializer->serialize($data, 'json');

        return new JsonResponse($serializedData, JsonResponse::HTTP_OK, [], true);
    }
}