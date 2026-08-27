<?php

namespace Kudu\CTKudu\DTO\PurchaseOrders;


final readonly class PurchaseOrderItemsData
{
    public function __construct(
        public ?string $referenceNumber,
        public ?string $productNumber,
        public ?string $vendorProductNumber,
        public ?string $vendorPackageOrder,
        public ?string $quantityConfirm,
        public ?string $vendorPackage,
        public ?string $storageCode,
        public ?string $poLineNumber,
    )
    {

    }

    public static function collection(array $multi_data_array , string $reference_number): array
    {
        return array_map(
            fn(array $data) => self::fromArray($data , $reference_number),
            $multi_data_array
        );
    }

    public static function fromArray(array $data, string $reference_number): self
    {

        return new self(
            referenceNumber: $reference_number,
            productNumber: $data['productNumber'] ?? null,
            vendorProductNumber: $data['vendorProductNumber'] ?? null,
            vendorPackageOrder: $data['vendorPackageOrder'] ?? null,
            quantityConfirm: $data['quantityConfirm'] ?? null,
            vendorPackage: $data['vendorPackage'] ?? null,
            storageCode: $data['storageCode'] ?? null,
            poLineNumber: $data['poLineNumber'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'referenceNumber' => $this->referenceNumber,
            'productNumber' => $this->productNumber,
            'vendorProductNumber' => $this->vendorProductNumber,
            'vendorPackageOrder' => $this->vendorPackageOrder,
            'quantityConfirm' => $this->quantityConfirm,
            'vendorPackage' => $this->vendorPackage,
            'storageCode' => $this->storageCode,
            'poLineNumber' => $this->poLineNumber,
        ];
    }
}
