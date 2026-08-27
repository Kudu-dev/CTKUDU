<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\UnitOfMeasureData;

class UnitOfMeasureEndpoint
{
    private const ENDPOINT = '/packagetype/v2/getAllPackageTypes';
    private const SIGNATURE = 'uom';
    private CrunchTimeClient $client;

    public function __construct()
    {
        $this->client = new CrunchTimeClient(self::SIGNATURE);
    }

    /**
     * Get all Unit of Measure data.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function get(array $query = []): array
    {
        return UnitOfMeasureData::collection($this->client->get(self::ENDPOINT, $query));
    }


}
