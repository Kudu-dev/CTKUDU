<?php

namespace Kudu\CTKudu\DTO\Inventories;

use Carbon\Carbon;

final readonly class CurrentInventoryData
{
    public function __construct(
        public ?string $location_code,
        public ?string $location_name,
        public ?string $product_name,
        public ?string $product_number,
        public ?string $category_name,
        public ?string $sub_category_name,
        public ?string $micro_category_name,
        public ?string $gl_code,
        public ?string $gl_description,
        public ?string $inventory_unit,
        public ?float  $unit_price,
        public ?float  $on_hand_quantity,
        public ?float  $on_hand_value,
        public ?float  $in_transit_quantity,
        public ?float  $in_transit_value,
        public ?float  $total_quantity,
        public ?float  $total_value,
        public ?float  $customer_order_quantity,
        public ?float  $vendor_order_quantity,
        public ?string $pull_date
    )
    {
    }

    public static function collection(array $multi_data_array, $pull_date): array
    {
        $multi_data_array = $multi_data_array[0]['physicalInventoryStandardDetailDetails'] ?? [];

        return array_map(
            fn(array $data) => self::fromArray($data, $pull_date),
            $multi_data_array
        );
    }


    public static function fromArray(array $data, $pull_date): self
    {

        return new self(
            location_code: $data['locationCode'],
            location_name: $data['locationName'],
            product_name: $data['productName'],
            product_number: $data['productNumber'],
            category_name: $data['categoryName'],
            sub_category_name: $data['subCategoryName'],
            micro_category_name: $data['microCategoryName'],
            gl_code: $data['glCode'],
            gl_description: $data['glDescription'],
            inventory_unit: $data['inventoryUnit'],
            unit_price: $data['unitPrice'],
            on_hand_quantity: $data['onHandQuantity'],
            on_hand_value: $data['onHandValue'],
            in_transit_quantity: $data['inTransitQuantity'],
            in_transit_value: $data['inTransitValue'],
            total_quantity: $data['totalQuantity'],
            total_value: $data['totalValue'],
            customer_order_quantity: $data['customerOrderQuantity'],
            vendor_order_quantity: $data['vendorOrderQuantity'],
            pull_date: $pull_date,

        );
    }


    public function toArray(): array
    {
        return [
            'location_code' => $this->location_code,
            'location_name' => $this->location_name,
            'product_name' => $this->product_name,
            'product_number' => $this->product_number,
            'category_name' => $this->category_name,
            'sub_category_name' => $this->sub_category_name,
            'micro_category_name' => $this->micro_category_name,
            'gl_code' => $this->gl_code,
            'gl_description' => $this->gl_description,
            'inventory_unit' => $this->inventory_unit,
            'unit_price' => $this->unit_price,
            'on_hand_quantity' => $this->on_hand_quantity,
            'on_hand_value' => $this->on_hand_value,
            'in_transit_quantity' => $this->in_transit_quantity,
            'in_transit_value' => $this->in_transit_value,
            'total_quantity' => $this->total_quantity,
            'total_value' => $this->total_value,
            'customer_order_quantity' => $this->customer_order_quantity,
            'vendor_order_quantity' => $this->vendor_order_quantity,
            'pull_date' => $this->pull_date,
        ];
    }
}
