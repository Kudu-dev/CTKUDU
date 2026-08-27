<?php

namespace Kudu\CTKudu\DTO;

use Illuminate\Support\Carbon;

final readonly class LocationData
{
    public function __construct(
        public string  $code,
        public string  $name,
        public string  $city,
        public string  $market,
        public string  $email,
        public string  $active_flag,
        public string  $franchise_Code,
        public string  $baseCurrency,
        public string  $allow_auto_transfer,
        public string  $allow_auto_post,
        public ?string $last_inventory_post_date
    )
    {

    }

    public static function fromArray(array $data): self
    {
        $locationNameAddressDetails = $data['locationNameAddressDetails'][0] ?? [];
        $locationDetailDetails = $data['locationDetailDetails'][0] ?? [];

        return new self(
            code: $data['locationCode'],
            name: $locationNameAddressDetails['locationName'],
            city: $locationNameAddressDetails['city'],
            market: $locationDetailDetails['market'],
            email: $locationNameAddressDetails['eMail'] ?? '-',
            active_flag: $locationNameAddressDetails['activeFlag'],
            franchise_Code: $locationNameAddressDetails['franchiseCode'],
            baseCurrency: $locationDetailDetails['baseCurrency'],
            allow_auto_transfer: $locationDetailDetails['allowAutoTransfer'],
            allow_auto_post: $locationDetailDetails['allowAutoPost'],
            last_inventory_post_date: isset($locationDetailDetails['lastInventoryPost']) ? Carbon::createFromFormat('m/d/Y', str_replace(' ', '', $locationDetailDetails['lastInventoryPost']))->toDateString() : null,
        );
    }

    public static function collection(array $multi_data_array): array
    {
        return array_map(
            fn(array $data) => self::fromArray($data),
            $multi_data_array
        );
    }


    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'city' => $this->city,
            'market' => $this->market,
            'email' => $this->email,
            'active_flag' => $this->active_flag,
            'franchise_Code' => $this->franchise_Code,
            'baseCurrency' => $this->baseCurrency,
            'allow_auto_transfer' => $this->allow_auto_transfer,
            'allow_auto_post' => $this->allow_auto_post,
            'last_inventory_post_date' => $this->last_inventory_post_date,
        ];
    }
}
