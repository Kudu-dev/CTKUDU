<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\ProductsData;

class ProductsEndpoint
{
    private const ENDPOINT = '/vendorproductpricing/v1/getAllVendorProductPricing';
    private const SIGNATURE = 'product';
    private CrunchTimeClient $client;

    public function __construct()
    {
        $this->client = new CrunchTimeClient(self::SIGNATURE);
    }

    /**
     * Get all CrunchTime products.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function get(array $query = []): array
    {
        return ProductsData::collection($this->client->get(self::ENDPOINT, $query));
    }


    /**
     * Get all CrunchTime products including those with null values.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getWithNull(array $query = []): array
    {
        return ProductsData::collection($this->client->get(self::ENDPOINT, [...$query, 'includeNull' => true]));
    }

    /**
     * Get all CrunchTime products excluding those with null values.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getWithoutNull(array $query = []): array
    {
        return ProductsData::collection($this->client->get(self::ENDPOINT, [...$query, 'includeNull' => false]));
    }

    public function getPrimaryProducts(array $query = []): array
    {
        $data = ProductsData::collection($this->client->get(self::ENDPOINT, [...$query, 'includeNull' => false]));

        return array_filter($data, function ($product) {
            return !$product->is_secondary;
        });
    }

    public function getSecondaryProducts(array $query = []): array
    {
        $data = ProductsData::collection($this->client->get(self::ENDPOINT, [...$query, 'includeNull' => false]));

        return array_filter($data, function ($product) {
            return $product->is_secondary;
        });
    }


    /**
     * Find a product by its product number.
     *
     * @param string $productNumber The product number to search for. (e.g., "P1265")
     * @return array|null The product data if found, or null if not found.
     */
    public function findByProductNumber(string $productNumber): array|null
    {
        $products = $this->get(['productNumber' => $productNumber]);

        return $products ?? null;
    }

    /**
     * Find products by its vendor.
     *
     * @param string $vendor The vendor to search for. (e.g., "WAREHOUSE", ...)
     * @return array|null The product data if found, or null if not found.
     */
    public function findByVendor(string $vendor): array|null
    {
        $products = $this->get(['vendor' => $vendor]);

        return $products ?? null;
    }

    /**
     * Find products by its MARKET.
     *
     * @param string $market The market to search for. (e.g., "CENTRAL", "WESTERN" , "NORTHERN" ...)
     * @return array|null The product data if found, or null if not found.
     */
    public function findByMarket(string $market): array|null
    {
        $products = $this->get(['market' => $market]);

        return $products ?? null;
    }

}
