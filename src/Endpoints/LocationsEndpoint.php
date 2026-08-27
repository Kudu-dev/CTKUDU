<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\LocationData;

class LocationsEndpoint
{
    private const ENDPOINT = '/location/v1/getAllLocations';
    private const SIGNATURE = 'location';
    private CrunchTimeClient $client;

    public function __construct()
    {
        $this->client = new CrunchTimeClient(self::SIGNATURE);
    }

    /**
     * Get all CrunchTime locations.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function get(array $query = []): array
    {
        return LocationData::collection($this->client->get(self::ENDPOINT, $query));
    }


    /**
     * Get active CrunchTime locations only.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getActive(array $query = []): array
    {
        return LocationData::collection($this->client->get(self::ENDPOINT, [...$query, 'activeFlag' => true]));
    }

    /**
     * Get disabled CrunchTime locations only.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getDisabled(array $query = []): array
    {
        return LocationData::collection($this->client->get(self::ENDPOINT, [...$query, 'activeFlag' => false]));
    }


    /**
     * Get disabled CrunchTime locations only.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function getWhereLastInventoryPostDateNotNull(): array
    {
        $locations = LocationData::collection($this->client->get(self::ENDPOINT, ['activeFlag' => true]));

        return array_filter($locations, function (LocationData $location) {
            return $location->last_inventory_post_date != null;
        });
    }


    /**
     * Find a location by its location code.
     *
     * @param string $locationCode The location code to search for.
     * @return array|null The location data if found, or null if not found.
     */
    public function findByLocationCode(string $locationCode): LocationData|null
    {
        $locations = $this->get(['locationCode' => $locationCode]);

        $locations = LocationData::fromArray($locations[0]);
        return $locations ?? null;
    }

}
