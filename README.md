# CTKudu

A Laravel package for integrating with CrunchTime web services through endpoint-specific clients.

This package provides a structured wrapper around the CrunchTime API so a Laravel application can read operational data such as locations, products, inventory, recipes, and purchase orders without building raw HTTP calls across the application.

## Features

- Location data access
- Unit-of-measure retrieval
- Vendor pricing and company product data
- Inventory fetches by location and date
- Recipe headers and ingredient details
- Purchase order headers and details
- Configurable base URL, authentication headers, retries, and logging

## Requirements

- PHP 8.2+
- Laravel 11 or 12
- Access to the CrunchTime API endpoint and credentials for your site

## Installation

Install the package with Composer:

```bash
composer require kudu/ctkudu
```

Laravel auto-discovers the service provider from the package metadata, so no manual service registration is typically required.

## Configuration

Publish the package configuration:

```bash
php artisan vendor:publish --tag=ctkudu-config
```

This creates `config/ctkudu.php`.

The package ships with a default config that wires the token settings to environment variables. In the current version, some endpoint tokens reuse the same `CTKUDU_TOKEN_PRODUCT` value, so check the published config before changing it for production.

```php
return [
    'base_url' => env('CTKUDU_BASE_URL', 'https://webservices.net-chef.com/'),
    'tokens' => [
        'location' => env('CTKUDU_TOKEN_LOCATION'),
        'uom' => env('CTKUDU_TOKEN_UOM'),
        'product' => env('CTKUDU_TOKEN_PRODUCT'),
        'company_product' => env('CTKUDU_TOKEN_PRODUCT'),
        'inventory' => env('CTKUDU_TOKEN_PRODUCT'),
        'recipe' => env('CTKUDU_TOKEN_RECIPE'),
        'purchaseorder' => env('CTKUDU_TOKEN_PURCHASEORDER'),
    ],
    'sitename' => env('CTKUDU_SITENAME'),
    'userid' => env('CTKUDU_USERID'),
    'password' => env('CTKUDU_PASSWORD'),
    'traceId' => env('CTKUDU_TRACEID'),
    'timeout' => (int) env('CTKUDU_TIMEOUT', 60),
    'connect_timeout' => (int) env('CTKUDU_CONNECT_TIMEOUT', 10),
    'retry' => [
        'times' => (int) env('CTKUDU_RETRY_TIMES', 3),
        'sleep' => (int) env('CTKUDU_RETRY_SLEEP', 500),
    ],
    'logging' => [
        'enabled' => (bool) env('CTKUDU_LOGGING', false),
        'channel' => env('CTKUDU_LOG_CHANNEL'),
    ],
];
```

Example `.env` values:

```env
CTKUDU_BASE_URL=https://webservices.net-chef.com/
CTKUDU_SITENAME=Kudu
CTKUDU_USERID=6104
CTKUDU_PASSWORD=your-password
CTKUDU_TOKEN_LOCATION=YOUR-TOKEN-HERE
CTKUDU_TOKEN_UOM=YOUR-TOKEN-HERE
CTKUDU_TOKEN_PRODUCT=YOUR-TOKEN-HERE
CTKUDU_TOKEN_RECIPE=YOUR-TOKEN-HERE
CTKUDU_TOKEN_COMPANY_PRODUCT=YOUR-TOKEN-HERE
CTKUDU_TOKEN_INVENTORY=YOUR-TOKEN-HERE
CTKUDU_TOKEN_PURCHASEORDER=YOUR-TOKEN-HERE
CTKUDU_TIMEOUT=60
CTKUDU_CONNECT_TIMEOUT=10
CTKUDU_RETRY_TIMES=3
CTKUDU_RETRY_SLEEP=500
CTKUDU_LOGGING=true
CTKUDU_LOG_CHANNEL=stack
```

## Package structure

The package exposes endpoint-specific classes under `Kudu\CTKudu\Endpoints`:

- `LocationsEndpoint`
- `UnitOfMeasureEndpoint`
- `ProductsEndpoint`
- `CompanyProductsEndpoint`
- `InventoryEndpoint`
- `RecipesEndpoint`
- `PurchaseOrdersEndpoint`

Each endpoint creates a `CrunchTimeClient` using the matching token signature and sends authenticated requests to the configured base URL.

## Usage examples

### Locations

```php
use Kudu\CTKudu\Endpoints\LocationsEndpoint;

$locations = app(LocationsEndpoint::class);

$allLocations = $locations->get();
$activeLocations = $locations->getActive();
$disabledLocations = $locations->getDisabled();
```

### Products

```php
use Kudu\CTKudu\Endpoints\ProductsEndpoint;

$products = app(ProductsEndpoint::class);

$allProducts = $products->get();
$productsWithoutNull = $products->getWithoutNull();
$primaryProducts = $products->getPrimaryProducts();
$secondaryProducts = $products->getSecondaryProducts();
```

### Company products

```php
use Kudu\CTKudu\Endpoints\CompanyProductsEndpoint;

$companyProducts = app(CompanyProductsEndpoint::class);

$all = $companyProducts->get();
$withDetails = $companyProducts->getWithDetails();
$withoutDetails = $companyProducts->getWithoutDetails();
```

### Inventory

```php
use Kudu\CTKudu\Endpoints\InventoryEndpoint;

$inventory = app(InventoryEndpoint::class);

$data = $inventory->getForLocation('07/31/2026', '123');
$allLocationsInventory = $inventory->getForAllLocations('07/31/2026');
```

Notes:

- Dates should be sent in `MM/DD/YYYY` format.
- `locationCode` is expected to be the CrunchTime location code used by the API.

### Recipes

```php
use Kudu\CTKudu\Endpoints\RecipesEndpoint;

$recipes = app(RecipesEndpoint::class);

$recipe = $recipes->getWithIngredients('P1265');
```

This returns an array with:

```php
[
    'header' => $recipeHeader,
    'ingredients' => $recipeIngredients,
]
```

### Purchase orders

```php
use Kudu\CTKudu\Endpoints\PurchaseOrdersEndpoint;

$orders = app(PurchaseOrdersEndpoint::class);

$headers = $orders->getOrdersHeader();
$allOrdersWithDetails = $orders->getAllOrdersWithDetails();
```

## Request behavior

The underlying `CrunchTimeClient`:

- builds the base URL with `Http::baseUrl(...)`
- sends `Accept: application/json`
- adds CrunchTime authentication headers
- disables SSL verification (`verify => false`) for the current API client configuration
- retries failed requests based on the configured retry values
- throws exceptions on invalid HTTP responses

## Logging

Logging can be enabled in the config:

```php
'logging' => [
    'enabled' => true,
    'channel' => 'stack',
],
```

The package uses `CTKuduLogger` for request-level logging.

## Notes and caveats

- This package is designed for Laravel applications and is not a standalone CLI tool.
- Most endpoint methods accept a `$query` array to pass additional API filters.
- Some endpoints are optimized for specific CrunchTime API payloads and may require parameters like `includeNull`, `includeDetails`, or `locationCode`.
- Keep your CrunchTime tokens and credentials in environment variables rather than hard-coding them into application code.

## License

This package is distributed under the project’s licensing terms. See the repository root for the applicable license details.
