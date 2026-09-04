<?php

namespace Kudu\CTKudu\Endpoints\Inventories;

use Exception;
use InvalidArgumentException;
use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\InventoryData;

class InventoryEndpoint
{
    private const ENDPOINT = '/inventory/physicalinventorystandard/v1/getAllPhysicalInventoryStandards?includeNull=false';
    private const SIGNATURE = 'inventory';

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
        return $this->client->get(self::ENDPOINT, $query);
    }


    /**
     * Get all Inventory data for a specific location and target date.
     *
     * @param string $target_date The target date in MM/DD/YYYY format.
     * @param string $store_code The store code.
     * @return array
     */
    public function getForLocation(string $target_date, string $store_code): array
    {
        $this->validateInputs($target_date, $store_code);

        return InventoryData::collection($this->client->get(self::ENDPOINT, ['inventoryDate' => $target_date, 'locationCode' => $store_code, 'includeNull' => false]), $target_date, $store_code);
    }


    public function getForAllLocations(string $target_date): array
    {
        // Validate target date format
        if (!preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/', $target_date)) {
            throw new InvalidArgumentException('Invalid target date format. Expected format: MM/DD/YYYY as string.');
        }

        $locations = app(LocationsEndpoint::class)->getWhereLastInventoryPostDateNotNull();

        print("Total Locations: " . count($locations) . "\n");

        $result = [];
        foreach ($locations as $location) {
            print("Processing Location: " . $location->code . "\n");
            try {
                $single_location_inventory = $this->getForLocation($target_date, $location->code);
                $result = array_merge($result, $single_location_inventory);
            } catch (Exception $e) {
                print("Error processing location " . $location->code . ": " . $e->getMessage() . "\n");
            }
        }
        return $result;
    }

    private function validateInputs($target_date, $store_code): void
    {
        // target date should be formatted as 07/31/2026 which mean month/day/year
        if (!preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/', $target_date)) {
            throw new InvalidArgumentException('Invalid target date format. Expected format: MM/DD/YYYY as string.');
        }

        // store code should be a string
        if (!is_string($store_code)) {
            throw new InvalidArgumentException('Invalid store code format. Expected a string.');
        }
    }

}
