<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\UnitOfMeasureData;

class ScheduledExportsEndpoint
{
    private const ENDPOINT = '/scheduledexport/v1/getAllScheduledExports';
    private const SIGNATURE = 'transferred_purchase_orders';
    private CrunchTimeClient $client;

    public function __construct()
    {
        $this->client = new CrunchTimeClient(self::SIGNATURE);
    }

    public function get(array $query = []): array
    {
        return $this->client->get(self::ENDPOINT, $query);
    }

    /**
     * Fetches scheduled exports for a given export date.
     *
     * @param string $exportDate The export date in 'mm/dd/yyyy' format.
     * @param array $query Optional query parameters to include in the request.
     * @return array The scheduled exports data.
     * @throws \InvalidArgumentException If the export date is empty.
     */
    public function getScheduledExports(string $exportDate, array $query = []): array
    {
        if (empty($exportDate)) {
            throw new \InvalidArgumentException('Export date is required.');
        }

        $payload = [
            'userIdForDetails' => 6104,
            'extractName' => 'CT Inventory - Location Transfer, Delta',
            'exportType' => 'USER_DEFINED',
            'exportDate' => $exportDate
        ];

        return $this->client->get(self::ENDPOINT, [...$payload, ...$query]);
    }


}
