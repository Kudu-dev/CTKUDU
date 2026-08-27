<?php

namespace Kudu\CTKudu\DTO\Recipes;


final readonly class RecipeData
{
    public function __construct(
        public ?string $name,
        public ?string $number,
        public ?string $activeflag,
        public ?string $categoryname,
        public ?string $subcategoryname,
        public ?string $plunumber,
        public ?string $recipeposdecrement,
        public ?string $recipestatus,
        public ?string $batchpackagepackagetype,
        public ?string $batchquantity,
        public ?string $inventoryunitpackagetype,
        public ?string $portionyield,
        public ?string $portionamount,
        public ?string $issueunitonepackagetype,
        public ?string $issueunitoneyield,
        public ?string $issueunittwopackagetype,
        public ?string $issueunittwoyield,
        public ?string $recipeunitonepackagetype,
        public ?string $recipeunitoneyield,
        public ?string $price,
        public ?string $recipeclass,
        public ?string $recipeproductgroupname,
        public ?string $haccpcompliancename,
        public ?string $recipecomplexity,
        public ?string $reciperating,
        public ?string $preptimetype,
        public ?string $prepreportflag,
        public ?string $batchroundingbasedon,
        public ?string $prepreportconsrounding,
        public ?string $prepreportshelfmaxrounding,
        public ?string $lasttouchdate,
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
            name: $data['name'],
            number: $data['number'],
            activeflag: $data['activeFlag'] == "Y",
            categoryname: $data['categoryName'],
            subcategoryname: $data['subcategoryName'],
            plunumber: $data['pluNumber'],
            recipeposdecrement: $data['recipePosDecrement'],
            recipestatus: $data['recipeStatus'],
            batchpackagepackagetype: $data['batchPackagePackageType'],
            batchquantity: $data['batchQuantity'],
            inventoryunitpackagetype: $data['inventoryUnitPackageType'],
            portionyield: $data['portionYield'],
            portionamount: $data['portionAmount'],
            issueunitonepackagetype: $data['issueUnitOnePackageType'],
            issueunitoneyield: $data['issueUnitOneYield'],
            issueunittwopackagetype: $data['issueUnitTwoPackageType'],
            issueunittwoyield: $data['issueUnitTwoYield'],
            recipeunitonepackagetype: $data['recipeUnitOnePackageType'],
            recipeunitoneyield: $data['recipeUnitOneYield'],
            price: $data['price'],
            recipeclass: $data['recipeClass'],
            recipeproductgroupname: $data['recipeProductGroupName'],
            haccpcompliancename: $data['haccpComplianceName'],
            recipecomplexity: $data['recipeComplexity'],
            reciperating: $data['recipeRating'],
            preptimetype: $data['prepTimeType'],
            prepreportflag: $data['prepReportFlag'],
            batchroundingbasedon: $data['batchRoundingBasedOn'],
            prepreportconsrounding: $data['prepReportConsRounding'],
            prepreportshelfmaxrounding: $data['prepReportShelfMaxRounding'],
            lasttouchdate: $data['lastTouchDate']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'number' => $this->number,
            'activeflag' => $this->activeflag,
            'categoryname' => $this->categoryname,
            'subcategoryname' => $this->subcategoryname,
            'plunumber' => $this->plunumber,
            'recipeposdecrement' => $this->recipeposdecrement,
            'recipestatus' => $this->recipestatus,
            'batchpackagepackagetype' => $this->batchpackagepackagetype,
            'batchquantity' => $this->batchquantity,
            'inventoryunitpackagetype' => $this->inventoryunitpackagetype,
            'portionyield' => $this->portionyield,
            'portionamount' => $this->portionamount,
            'issueunitonepackagetype' => $this->issueunitonepackagetype,
            'issueunitoneyield' => $this->issueunitoneyield,
            'issueunittwopackagetype' => $this->issueunittwopackagetype,
            'issueunittwoyield' => $this->issueunittwoyield,
            'recipeunitonepackagetype' => $this->recipeunitonepackagetype,
            'recipeunitoneyield' => $this->recipeunitoneyield,
            'price' => $this->price,
            'recipeclass' => $this->recipeclass,
            'recipeproductgroupname' => $this->recipeproductgroupname,
            'haccpcompliancename' => $this->haccpcompliancename,
            'recipecomplexity' => $this->recipecomplexity,
            'reciperating' => $this->reciperating,
            'preptimetype' => $this->preptimetype,
            'prepreportflag' => $this->prepreportflag,
            'batchroundingbasedon' => $this->batchroundingbasedon,
            'prepreportconsrounding' => $this->prepreportconsrounding,
            'prepreportshelfmaxrounding' => $this->prepreportshelfmaxrounding,
            'lasttouchdate' => $this->lasttouchdate
        ];
    }
}
