<?php

namespace Kudu\CTKudu\DTO;

use Carbon\Carbon;

final readonly class ProductsData
{
    public function __construct(
        public string $product_number,
        public string $vendor_product_number,
        public string $name,
        public string $vendor,
        public string $vendor_code,
        public string $market,
        public bool $is_secondary,
        public string $pack_size,
        public string $inventory_unit,
        public string $conversion_to_inventory_unit,
        public string $price,
        public string $bid_sheet,
        public string $split_flag,
        public ?string $last_change_date,
        public ?string $begin_date,
    ) {
    }

    public static function collection(array $products): array
    {
        $result = [];

        foreach ($products as $product) {
            array_push(
                $result,
                ...self::fromProductWithSecondaries($product)
            );
        }

        return $result;
    }

    /**
     * Convert one CrunchTime product into:
     * - one primary product
     * - zero or more secondary vendor products
     *
     * @return array<int, self>
     */
    public static function fromProductWithSecondaries(array $product): array
    {
        $result = [
            self::fromArray($product),
        ];

        foreach ($product['secondaryVendorProducts'] ?? [] as $secondary) {
            $result[] = self::fromSecondaryArray(
                product: $product,
                secondary: $secondary
            );
        }

        return $result;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            product_number: (string) $data['productNumber'],
            vendor_product_number: (string) $data['vendorProductNumber'],
            name: (string) $data['productName'],
            vendor: (string) $data['vendor'],
            vendor_code: (string) $data['vendorCode'],
            market: (string) $data['market'],
            is_secondary: false,
            pack_size: (string) $data['vendorPackSize'],
            inventory_unit: (string) $data['inventoryUnit'],
            conversion_to_inventory_unit: (string) $data['conversionToInventoryUnit'],
            price: (string) $data['price'],
            bid_sheet: (string) $data['bidSheet'],
            split_flag: (string) $data['splitFlag'],
            last_change_date: self::formatDateTime($data['lastChangeDate'] ?? null),
            begin_date: self::formatDate($data['beginDate'] ?? null),
        );
    }

    private static function fromSecondaryArray(
        array $product,
        array $secondary
    ): self {
        return new self(
            product_number: (string) $product['productNumber'],
            vendor_product_number: (string) $secondary['vendorProductNumber'],
            name: (string) $secondary['vendorProductName'],
            vendor: (string) $product['vendor'],
            vendor_code: (string) $product['vendorCode'],
            market: (string) $product['market'],
            is_secondary: true,
            pack_size: (string) $secondary['vendorPackSize'],
            inventory_unit: (string) $product['inventoryUnit'],
            conversion_to_inventory_unit: (string) $secondary['conversionToInventoryUnit'],
            price: (string) $secondary['price'],
            bid_sheet: (string) $product['bidSheet'],
            split_flag: (string) $secondary['splitFlag'],
            last_change_date: self::formatDateTime($secondary['lastChangeDate'] ?? null),
            begin_date: self::formatDate($secondary['beginDate'] ?? null),
        );
    }

    private static function formatDateTime(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::createFromFormat(
            'm/d/Y H:i:s',
            str_replace(' ', '', $value)
        )->toDateString();
    }

    private static function formatDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::createFromFormat(
            'm/d/Y',
            str_replace(' ', '', $value)
        )->toDateString();
    }

    public function toArray(): array
    {
        return [
            'product_number' => $this->product_number,
            'vendor_product_number' => $this->vendor_product_number,
            'name' => $this->name,
            'vendor' => $this->vendor,
            'vendor_code' => $this->vendor_code,
            'market' => $this->market,
            'is_secondary' => $this->is_secondary,
            'pack_size' => $this->pack_size,
            'inventory_unit' => $this->inventory_unit,
            'conversion_to_inventory_unit' => $this->conversion_to_inventory_unit,
            'price' => $this->price,
            'bid_sheet' => $this->bid_sheet,
            'split_flag' => $this->split_flag,
            'last_change_date' => $this->last_change_date,
            'begin_date' => $this->begin_date,
        ];
    }
}
