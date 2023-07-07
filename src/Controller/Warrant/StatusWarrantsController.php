<?php

namespace App\Controller\Warrant;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Helper\OrmPaginationHelper;
use App\Repository\WarrantRepository;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class StatusWarrantsController extends AbstractController
{
    private WarrantRepository $warrantRepository;

    public function __construct(WarrantRepository $warrantRepository)
    {
        $this->warrantRepository = $warrantRepository;
    }

    /**
     * @throws QueryException
     */
    public function __invoke(Request $request, int $statusId): Paginator
    {
        $page         = (int)$request->query->get('page', OrmPaginationHelper::PAGE_DEFAULT);
        $itemsPerPage = (int)$request->query->get('itemsPerPage', OrmPaginationHelper::ITEMS_PER_PAGE_DEFAULT);

        return $this->warrantRepository->getWarrantsByStatus(
            $statusId,
            $page,
            $itemsPerPage
        );
    }
}