<?php

namespace Kudu\CTKudu\DTO;

use Carbon\Carbon;

final readonly class CompanyProductsData
{
    public function __construct(
        public string $company_product_number,
        public string $name,
        public string $active_flag,
        public string $category_name,
        public string $product_type,
        public string $INV_UNIT_PKG_TYPE,
        public string $INV_UNIT1_PKG_TYPE,
        public string $INV_UNIT2_PKG_TYPE,
        public string $ISSUE_UNIT1_CONV,
        public string $ISSUE_UNIT2_CONV,
        public string $RECIPE_UNIT1_PKG,
        public string $RECIPE_UNIT1_CONV,
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
        $data = $data['companyProductEnhancedHeaderDetails'] ?? [];

        return new self(
            company_product_number: $data['number'],
            name: $data['name'],
            active_flag: $data['activeFlag'],
            category_name: $data['categoryName'],
            product_type: $data['productType'],
            INV_UNIT_PKG_TYPE: $data['inventoryUnitPackageType'],
            INV_UNIT1_PKG_TYPE: $data['issueUnitOnePackageType'],
            INV_UNIT2_PKG_TYPE: $data['issueUnitTwoPackageType'],
            ISSUE_UNIT1_CONV: $data['issueUnitOneConversion'],
            ISSUE_UNIT2_CONV: $data['issueUnitTwoConversion'],
            RECIPE_UNIT1_PKG: $data['recipeUnitOnePackageType'],
            RECIPE_UNIT1_CONV: $data['recipeUnitOneConversion'],
        );
    }


    public function toArray(): array
    {
        return [
            'company_product_number' => $this->company_product_number,
            'name' => $this->name,
            'active_flag' => $this->active_flag,
            'category_name' => $this->category_name,
            'product_type' => $this->product_type,
            'INV_UNIT_PKG_TYPE' => $this->INV_UNIT_PKG_TYPE,
            'INV_UNIT1_PKG_TYPE' => $this->INV_UNIT1_PKG_TYPE,
            'INV_UNIT2_PKG_TYPE' => $this->INV_UNIT2_PKG_TYPE,
            'ISSUE_UNIT1_CONV' => $this->ISSUE_UNIT1_CONV,
            'ISSUE_UNIT2_CONV' => $this->ISSUE_UNIT2_CONV,
            'RECIPE_UNIT1_PKG' => $this->RECIPE_UNIT1_PKG,
            'RECIPE_UNIT1_CONV' => $this->RECIPE_UNIT1_CONV,
        ];
    }
}
