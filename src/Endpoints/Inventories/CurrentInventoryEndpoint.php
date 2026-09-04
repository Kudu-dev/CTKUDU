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

    /**
     * Get all CrunchTime Current Inventory data.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function get(array $query = []): array
    {
        return $this->client->get(self::ENDPOINT, $query);
    }


    /**
     * Get all Current Inventory data for a specific location.
     *
     * @param string $store_code The store code.
     * @return array
     */
    public function getForLocation(string $store_code, array $query = []): array
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        return $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, ...$query]);
//        return InventoryData::collection($this->client->get(self::ENDPOINT, ['inventoryDate' => $target_date, 'locationCode' => $store_code, 'includeNull' => false]), $target_date, $store_code);
    }

    /**
     * Get all Current Inventory data for a specific location and specific product.
     *
     * @param string $product_number The product number.
     * @param string $store_code The store code.
     * @return array
     */
    public function getForLocationAndProduct(string $product_number, string $store_code, array $query = []): array
    {
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }

        if (!is_string($product_number)) {
            throw new InvalidArgumentException('Invalid product number format. Expected a string.');
        }

        return $this->client->get(self::ENDPOINT, ['locationCode' => $store_code, 'productNumber' => $product_number, ...$query]);
//        return InventoryData::collection($this->client->get(self::ENDPOINT, ['inventoryDate' => $target_date, 'locationCode' => $store_code, 'includeNull' => false]), $target_date, $store_code);
    }

}
