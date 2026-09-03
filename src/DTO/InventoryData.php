<?php

namespace Kudu\CTKudu\DTO;

use Carbon\Carbon;

final readonly class InventoryData
{
    public function __construct(
        public ?string $location_number,
        public ?string $request_date,
        public ?string $inventory_date,
        public ?string $user_id,
        public ?string $storage_code,
        public ?string $product_number,
        public ?float  $quantity,
        public ?float  $alt_quantity_1,
        public ?float  $alt_quantity_2,
        public ?float  $alt_quantity_3,
        public ?string $inventory_unit
    )
    {
    }

    public static function collection(array $multi_data_array, $target_date, $store_number): array
    {
        $multi_data_array = $multi_data_array[0]['physicalInventoryStandardDetailDetails'] ?? [];

        return array_map(
            fn(array $data) => self::fromArray($data, $target_date, $store_number),
            $multi_data_array
        );
    }


    public static function fromArray(array $data, $target_date, $store_number): self
    {
        return new self(
            location_number: $store_number,
            request_date: Carbon::createFromFormat('m/d/Y', str_replace(' ', '', $target_date))->toDateString(),
            inventory_date: Carbon::createFromFormat('m/d/Y', $data['inventoryDate'])->toDateString(),
            user_id: $data['userId'],
            storage_code: $data['storageCode'],
            product_number: $data['productNumber'],
            quantity: $data['quantity'] ?? 0.0,
            alt_quantity_1: $data['altQuantity1'] ?? null,
            alt_quantity_2: $data['altQuantity2'] ?? null,
            alt_quantity_3: $data['altQuantity3'] ?? null,
            inventory_unit: $data['inventoryUnit'] ?? null
        );
    }


    public function toArray(): array
    {
        return [
            'location_number' => $this->location_number,
            'request_date' => $this->request_date,
            'inventory_date' => $this->inventory_date,
            'user_id' => $this->user_id,
            'storage_code' => $this->storage_code,
            'product_number' => $this->product_number,
            'quantity' => $this->quantity,
            'alt_quantity_1' => $this->alt_quantity_1,
            'alt_quantity_2' => $this->alt_quantity_2,
            'alt_quantity_3' => $this->alt_quantity_3,
            'inventory_unit' => $this->inventory_unit,
        ];
    }
}
