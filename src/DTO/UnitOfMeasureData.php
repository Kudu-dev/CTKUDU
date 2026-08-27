<?php

namespace Kudu\CTKudu\DTO;


final readonly class UnitOfMeasureData
{
    public function __construct(
        public ?string $package_type,
        public ?string $package_description,
        public ?string $package_inventory,
        public ?string $package_recipe,
        public ?string $package_purchasing,
        public ?string $package_line_check,
    )
    {

    }

    public static function collection(array $multi_data_array): array
    {
        $multi_data_array = $multi_data_array[0]['packageTypeDetailDetails'] ?? [];
        return array_map(
            fn(array $data) => self::fromArray($data),
            $multi_data_array
        );
    }

    public static function fromArray(array $data): self
    {

        return new self(
            package_type: $data['packageType'],
            package_description: $data['packageDescription'],
            package_inventory: $data['packageInventory'],
            package_recipe: $data['packageRecipe'],
            package_purchasing: $data['packagePurchasing'],
            package_line_check: $data['packageLineCheck'],
        );
    }

    public function toArray(): array
    {
        return [
            'package_type' => $this->package_type,
            'package_description' => $this->package_description,
            'package_inventory' => $this->package_inventory,
            'package_recipe' => $this->package_recipe,
            'package_purchasing' => $this->package_purchasing,
            'package_line_check' => $this->package_line_check,
        ];
    }
}
