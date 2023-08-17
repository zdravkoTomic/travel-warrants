<?php

namespace App\WebService;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private const EXCHANGE_RATE_API = 'https://api.hnb.hr/tecajn-eur/v3';

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function convertByMiddleRateToDomicileCurrency(float $originalAmount, string $currencyCode): float
    {
        $response = $this->client->request(
            'GET',
            self::EXCHANGE_RATE_API,
            [
                'query' => [
                    'valuta' => $currencyCode
                ]
            ]
        );

        $statusCode = $response->getStatusCode();

        if (!$statusCode) {
            throw new HttpException(
                $statusCode,
                sprintf('Exchange rate API call failed with status code %d', $statusCode)
            );
        }

        $content = $response->toArray();

        $middleExchangeRate = (float)str_replace(',', '.', $content[0]['srednji_tecaj']);

        return round($originalAmount * $middleExchangeRate, 2);
    }
}