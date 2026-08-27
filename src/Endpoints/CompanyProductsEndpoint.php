<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\CompanyProductsData;
use Kudu\CTKudu\DTO\ProductsData;

class CompanyProductsEndpoint
{
    private const ENDPOINT = '/companyproduct/v1/getAllCompanyProductsEnhanced';
    private const SIGNATURE = 'company_product';
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
        return CompanyProductsData::collection($this->client->get(self::ENDPOINT, $query));
    }


    /**
     * Get all CrunchTime company products excluding those with null values.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getWithDetails(array $query = []): array
    {
        return CompanyProductsData::collection($this->client->get(self::ENDPOINT, [...$query, 'includeDetails' => true, 'includeNull' => false]));
    }

    /**
     * Get all CrunchTime company products excluding those with null values and excluding details.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getWithoutDetails(array $query = []): array
    {
        return CompanyProductsData::collection($this->client->get(self::ENDPOINT, [...$query, 'includeDetails' => false, 'includeNull' => false]));
    }


    /**
     * Find a company product by its product number.
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
     * Find products by its category.
     *
     * @param string $category The vendor to search for.
     * @return array|null The product data if found, or null if not found.
     */
    public function findByCategory(string $category): array|null
    {
        $products = $this->get(['category' => $category]);

        return $products ?? null;
    }

    /**
     * Find products by its Concept.
     *
     * @param string $concept The market to search for. (e.g., "CENTRAL", "WESTERN" , "NORTHERN" ...)
     * @return array|null The product data if found, or null if not found.
     */
    public function findByMarket(string $concept): array|null
    {
        $products = $this->get(['concept' => $concept]);

        return $products ?? null;
    }

    /**
     * Find products by its Concept.
     *
     * @param string $subCategory The market to search for. (e.g., "CENTRAL", "WESTERN" , "NORTHERN" ...)
     * @return array|null The product data if found, or null if not found.
     */
    public function findBySubCategory(string $subCategory): array|null
    {
        $products = $this->get(['subCategory' => $subCategory]);

        return $products ?? null;
    }

}
