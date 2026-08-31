<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\UnitOfMeasureData;

class TransferredPurchaseOrderEndpoint
{
    private const ENDPOINT = '/scheduledexport/v1/downloadScheduledExport';
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

    public function getTransferredPurchaseOrders(string $batchNumber, string $exportType, string $extractHistoryId, array $query = []): array
    {
        if (empty($batchNumber) || empty($exportType) || empty($extractHistoryId)) {
            throw new \InvalidArgumentException('Batch number, export type, and extract history ID are required.');
        }

        $payload = [
            'batchNumber' => $batchNumber,
            'exportType' => $exportType,
            'extractHistoryId' => $extractHistoryId
        ];

        return $this->client->get(self::ENDPOINT, [...$payload, ...$query]);
    }


}
