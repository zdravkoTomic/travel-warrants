<?php

namespace App\Controller\Warrant;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Helper\OrmPaginationHelper;
use App\Repository\WarrantRepository;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UserGroupStatusWarrantsController extends AbstractController
{
    private WarrantRepository $warrantRepository;

    public function __construct(WarrantRepository $warrantRepository)
    {
        $this->warrantRepository = $warrantRepository;
    }

    /**
     * @throws QueryException
     */
    public function __invoke(Request $request, int $employeeId, int $groupStatusId): Paginator
    {
        $page         = (int)$request->query->get('page', OrmPaginationHelper::PAGE_DEFAULT);
        $itemsPerPage = (int)$request->query->get('itemsPerPage', OrmPaginationHelper::ITEMS_PER_PAGE_DEFAULT);

        return $this->warrantRepository->getWarrantsForUserByGroupStatus(
            $employeeId,
            $groupStatusId,
            $page,
            $itemsPerPage
        );
    }
}