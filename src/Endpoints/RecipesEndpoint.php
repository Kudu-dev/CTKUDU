<?php

namespace Kudu\CTKudu\Endpoints;

use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\DTO\Recipes\RecipeData;
use Kudu\CTKudu\DTO\Recipes\RecipeIngredientsData;

class RecipesEndpoint
{
    private const ENDPOINT = '/recipe/v2/getAllRecipesEnhanced';
    private const SIGNATURE = 'recipe';
    private CrunchTimeClient $client;

    public function __construct()
    {
        $this->client = new CrunchTimeClient(self::SIGNATURE);
    }

    /**
     * Get all recipes from the CrunchTime API.
     *
     * @param array<string, mixed> $query Additional query parameters.
     * @return array
     */
    public function get(array $query = []): array
    {
        return $this->client->get(self::ENDPOINT, $query);
    }

    public function getHeaderOnly(string $productNumber, array $query = []): RecipeData|array
    {
        $recipe_header = $this->client->get(self::ENDPOINT, [...$query, 'includeDetails' => 'false', 'includeNull' => 'true', 'productNumber' => $productNumber]);

        $recipe_header = $recipe_header[0]['recipeEnhancedHeaderDetails'] ?? null;

        return $recipe_header ? RecipeData::fromArray($recipe_header) : [];
    }


    public function getWithIngredients(string $productNumber, array $query = []): array
    {
        $recipe = $this->client->get(self::ENDPOINT, [...$query, 'includeDetails' => 'true', 'includeNull' => 'true', 'productNumber' => $productNumber]);

        $recipe_header = $recipe[0]['recipeEnhancedHeaderDetails'] ?? null;

        if (!$recipe_header) {
            return [];
        }

        $recipe_header = RecipeData::fromArray($recipe_header);


        $recipe_ingredients = $recipe[0]['recipeEnhancedComponentDetails'] ?? [];

        if(!$recipe_ingredients) {
            return [
                'header' => $recipe_header,
                'ingredients' => [],
            ];
        }

        $recipe_ingredients = RecipeIngredientsData::collection($recipe_ingredients, $recipe_header->plunumber, $recipe_header->name);

        return [
            'header' => $recipe_header,
            'ingredients' => $recipe_ingredients,
        ];

    }


}
