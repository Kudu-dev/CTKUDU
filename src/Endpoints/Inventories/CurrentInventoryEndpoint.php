<?php

namespace Kudu\CTKudu\Endpoints\Inventories;

use Exception;
use InvalidArgumentException;
use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\InventoryData;

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
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        $currentInventoryDetails = [];

        $products = $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'pageSize' => 100, ...$query]);

        $currentInventoryDetails = array_merge($currentInventoryDetails, $products['currentInventoryDetails']['currentInventoryDetailDetails']);

        if ($products['hasNext']) {
            $page_number = 2;
            while ($products['hasNext']) {
                $products = $this->getForLocationByPageNumber($page_number, $store_code, $query);
                $currentInventoryDetails = array_merge($currentInventoryDetails, $products['currentInventoryDetails']['currentInventoryDetailDetails']);
                $page_number++;
            }
        }

        return $currentInventoryDetails;
    }

    public function getForLocationByPageNumber(string $page_number, string $store_code, array $query = []): array
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        return $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'pageSize' => 100, 'pageNumber' => $page_number, ...$query]);
    }


    public function getForLocationAndProduct(string $product_number, string $store_code, array $query = []): array
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        if (!is_string($product_number)) {
            throw new InvalidArgumentException('Invalid product number format. Expected a string.');
        }

        return $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'productNumber' => $product_number, 'pageSize' => 100, ...$query]);
    }


}
