<?php

namespace Kudu\CTKudu\Endpoints;

use Carbon\Carbon;
use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\PurchaseOrders\PurchaseOrderData;
use Kudu\CTKudu\DTO\PurchaseOrders\PurchaseOrderItemsData;

class PurchaseOrdersEndpoint
{
    private const ENDPOINT = '/purchaseorder/v1/getAllPurchaseOrders';
    private const SIGNATURE = 'purchaseorder';
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
        return $this->client->get(self::ENDPOINT, $query);
    }


    public function getOrdersHeader(array $query = []): array
    {
        return PurchaseOrderData::collection($this->client->get(self::ENDPOINT, [
            ...$this->generateDefaultPayload(),
            ...$query
        ]));
    }


    public function getOrdersDetails(string $reference_number, string $transaction_number, string $location_code, array $query = []): array
    {
        return PurchaseOrderItemsData::collection($this->client->get(self::ENDPOINT, [
            'includeDetails' => true,
            'locationCode' => $location_code,
            'purchaseOrderNumber' => $transaction_number,
            ...$query
        ])[0]['details'], $reference_number);

    }

    public function getAllOrdersWithDetails(array $query = []): array
    {
        ini_set('memory_limit', '1024M'); // Temporarily increase memory limit to 1GB
        $result = [];
        $orders = $this->getOrdersHeader($query);
        print("Total Orders: " . count($orders) . "\n");
        foreach ($orders as $order) {
            print("Processing Order: " . $order->referenceNumber . "\n");
            $result[] = [
                'header' => $order,
                'details' => $this->getOrdersDetails($order->referenceNumber, $order->transactionNumber, $order->locationCode)
            ];
        }
        return $result;
    }

    private function generateDefaultPayload(): array
    {
        $dateStart = Carbon::yesterday()->format('m/d/Y 00:00:00');
        $dateEnd = Carbon::today()->format('m/d/Y 00:30:00');
        return [
            'orderType' => 'VO',
            'submitDateStart' => $dateStart,
            'submitDateEnd' => $dateEnd,
            'status' => 7,
            'vendorCode' => 'WR0',
        ];
    }


}
