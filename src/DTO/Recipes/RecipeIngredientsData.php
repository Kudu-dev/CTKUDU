<?php

namespace Kudu\CTKudu\DTO\Recipes;


final readonly class RecipeIngredientsData
{
    public function __construct(
        public ?string $plunumber,
        public ?string $name,
        public ?string $number,
        public ?string $quantity,
        public ?string $majoringredient,
        public ?string $recipepackage,
        public ?string $preproduction,
        public ?string $specialinstruction1,
        public ?string $specialinstruction2,
        public ?string $scalingfactor,
        public ?string $sequence_number,
    )
    {

    }

    public static function collection(array $multi_data_array, $headar_number, $header_name): array
    {
        return array_map(
            fn(array $data) => self::fromArray($data, $headar_number, $header_name),
            $multi_data_array
        );
    }

    public static function fromArray(array $data, $headar_number, $header_name): self
    {
        return new self(
            plunumber: $headar_number,
            name: $data['name'],
            number: $data['number'],
            quantity: $data['quantity'],
            majoringredient: $data['majorIngredient'],
            recipepackage: $data['recipePackage'],
            preproduction: $data['preProduction'],
            specialinstruction1: $data['specialInstruction1'],
            specialinstruction2: $data['specialInstruction2'],
            scalingfactor: $data['scalingFactor'],
            sequence_number: $data['sequence'],
        );
    }

    public function toArray(): array
    {
        return [
            'plunumber' => $this->plunumber,
            'name' => $this->name,
            'number' => $this->number,
            'quantity' => $this->quantity,
            'majoringredient' => $this->majoringredient,
            'recipepackage' => $this->recipepackage,
            'preproduction' => $this->preproduction,
            'specialinstruction1' => $this->specialinstruction1,
            'specialinstruction2' => $this->specialinstruction2,
            'scalingfactor' => $this->scalingfactor,
        ];
    }
}
