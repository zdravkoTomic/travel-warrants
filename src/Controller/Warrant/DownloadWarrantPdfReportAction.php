<?php

namespace App\Controller\Warrant;

use App\Entity\Warrant;
use App\Service\Warrant\Report\WarrantPdfReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DownloadWarrantPdfReportAction extends AbstractController
{
    private WarrantPdfReportService $pdfReportService;

    public function __construct(WarrantPdfReportService $pdfReportService)
    {
        $this->pdfReportService = $pdfReportService;
    }

    public function __invoke(Warrant $warrant): Response
    {
        // Generate the PDF file based on entity data
        $data = $this->pdfReportService->getInitialReporData($warrant);

        $html = $this->renderView('reports/warrant_initial_pdf_report.html.twig', $data);

        $content = $this->pdfReportService->createInitialReportContent($html);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="example.pdf"');

        return $response;
    }
}