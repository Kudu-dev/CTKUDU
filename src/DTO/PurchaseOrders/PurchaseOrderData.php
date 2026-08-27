<?php

namespace Kudu\CTKudu\DTO\PurchaseOrders;


final readonly class PurchaseOrderData
{
    public function __construct(
        public ?string $expectedDeliveryDate,
        public ?string $referenceNumber,
        public ?string $createDate,
        public ?string $locationCode,
        public ?string $postDate,
        public ?string $city,
        public ?string $invoiceTotal,
        public ?string $transactionNumber,
        public ?string $invoiceNumber,
        public ?string $orderType,
        public ?string $submitUser,
        public ?string $confirmUser,
        public ?string $reconcileUser,
        public ?string $submitDate,
    )
    {

    }

    public static function collection(array $multi_data_array): array
    {
        return array_map(
            fn(array $data) => self::fromArray($data),
            $multi_data_array
        );
    }

    public static function fromArray(array $data): self
    {

        return new self(
            expectedDeliveryDate: date('Y-m-d', strtotime($data['expectedDeliveryDate'])),
            referenceNumber: $data['referenceNumber'],
            createDate: date('Y-m-d H:i:s', strtotime($data['createDate'])),
            locationCode: $data['locationCode'],
            postDate: date('Y-m-d', strtotime($data['postDate'])),
            city: $data['marketName'],
            invoiceTotal: $data['invoiceTotal'],
            transactionNumber: $data['transactionNumber'],
            invoiceNumber: $data['invoiceNumber'] ?? null,
            orderType: $data['orderType'],
            submitUser: $data['submitUser'],
            confirmUser: $data['confirmUser'],
            reconcileUser: $data['reconcileUser'] ?? null,
            submitDate: date('Y-m-d H:i:s', strtotime($data['submitDate'])) >= date('Y-m-d 00:00:00') ? date('Y-m-d H:i:s', strtotime($data['submitDate'] . ' -1 day')) : date('Y-m-d H:i:s', strtotime($data['submitDate'])),
        );
    }

    public function toArray(): array
    {
        return [
            'expectedDeliveryDate' => $this->expectedDeliveryDate,
            'referenceNumber' => $this->referenceNumber,
            'createDate' => $this->createDate,
            'locationCode' => $this->locationCode,
            'postDate' => $this->postDate,
            'city' => $this->city,
            'invoiceTotal' => $this->invoiceTotal,
            'transactionNumber' => $this->transactionNumber,
            'invoiceNumber' => $this->invoiceNumber,
            'orderType' => $this->orderType,
            'submitUser' => $this->submitUser,
            'confirmUser' => $this->confirmUser,
            'reconcileUser' => $this->reconcileUser,
            'submitDate' => $this->submitDate,
        ];
    }
}
