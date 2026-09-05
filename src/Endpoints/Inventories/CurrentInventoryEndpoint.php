<?php

namespace Kudu\CTKudu\Endpoints\Inventories;

use Carbon\Carbon;
use InvalidArgumentException;
use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\Inventories\CurrentInventoryData;

class CurrentInventoryEndpoint
{
    private const ENDPOINT = '/inventory/currentinventory/v1/getCurrentInventoryByPage';
    private const SIGNATURE = 'inventory';

    private CrunchTimeClient $client;

    public function __construct()
    {
        $this->client = new CrunchTimeClient(self::SIGNATURE);
    }


    public function get(array $query = []): array
    {
        return $this->client->get(self::ENDPOINT, $query);
    }


    public function getForLocation(string $store_code, array $query = []): array
    {
        $this->validateStoreCode($store_code);

        $currentInventoryDetails = [];

        $page_number = 1;
        $hasNext = true;

        while ($hasNext) {
            $products = $this->getForLocationByPageNumber($page_number, $store_code, $query);
            $currentInventoryDetails = array_merge($currentInventoryDetails, $this->formatResponse($products));

            $hasNext = $products['hasNext'] ?? false;
            $page_number++;
        }

        $today_date = Carbon::now()->format('Y-m-d H:i:s');
        return CurrentInventoryData::collection($currentInventoryDetails, $today_date);
    }

    public function getForLocationByPageNumber(int $page_number, string $store_code, array $query = []): array
    {
        $this->validateStoreCode($store_code);

        if (!$page_number) {
            throw new InvalidArgumentException('Invalid page number. Expected a positive integer.');
        }

        return $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'pageSize' => 100, 'pageNumber' => $page_number, ...$query]);
    }


    public function getForLocationAndProduct(string $product_number, string $store_code, array $query = []): array
    {
        $this->validateStoreCode($store_code);

        if (!is_string($product_number)) {
            throw new InvalidArgumentException('Invalid product number format. Expected a string.');
        }

        $products = $this->formatResponse($this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'productNumber' => $product_number, 'pageSize' => 100, ...$query]));
        return CurrentInventoryData::collection($products, Carbon::now()->format('Y-m-d H:i:s'));
    }

    private function validateStoreCode(string $store_code): void
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }
    }

    private function formatResponse(array $response): array
    {
        return $response['currentInventoryDetails'][0]['currentInventoryDetailDetails'] ?? [];
    }
}
