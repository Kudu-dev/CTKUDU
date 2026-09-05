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

    private function formatResponse(array $response): array
    {
        return $response['currentInventoryDetails'][0]['currentInventoryDetailDetails'] ?? [];
    }

    public function get(array $query = []): array
    {
        return $this->client->get(self::ENDPOINT, $query);
    }


    public function getForLocation(string $store_code, array $query = []): array
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        $currentInventoryDetails = [];

        $products = $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'pageSize' => 100, ...$query]);

        $currentInventoryDetails = array_merge($currentInventoryDetails, $this->formatResponse($products));

        if ($products['hasNext']) {
            $page_number = 2;
            while ($products['hasNext']) {
                $products = $this->getForLocationByPageNumber($page_number, $store_code, $query);
                $currentInventoryDetails = array_merge($currentInventoryDetails, $this->formatResponse($products));
                $page_number++;
            }
        }


        $today_date = Carbon::now()->format('Y-m-d');
        return CurrentInventoryData::collection($currentInventoryDetails, $today_date);
    }

    public function getForLocationByPageNumber(string $page_number, string $store_code, array $query = []): array
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        return $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'pageSize' => 100, 'pageNumber' => $page_number, ...$query]);
    }


    public function getForLocationAndProduct(string $product_number, string $store_code, array $query = []): CurrentInventoryData
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        if (!is_string($product_number)) {
            throw new InvalidArgumentException('Invalid product number format. Expected a string.');
        }

        $products = $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'productNumber' => $product_number, 'pageSize' => 100, ...$query]);
        $products = $this->formatResponse($products);

        print_r($products); // Debugging line to check the structure of $products

        return CurrentInventoryData::fromArray($products, Carbon::now()->format('Y-m-d'));
    }


}
