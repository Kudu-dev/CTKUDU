<?php

namespace Kudu\CTKudu;

use Illuminate\Support\ServiceProvider;
use Kudu\CTKudu\Client\CrunchTimeClient;
use Kudu\CTKudu\Endpoints\CompanyProductsEndpoint;
use Kudu\CTKudu\Endpoints\InventoryEndpoint;
use Kudu\CTKudu\Endpoints\LocationsEndpoint;
use Kudu\CTKudu\Endpoints\ProductsEndpoint;
use Kudu\CTKudu\Endpoints\PurchaseOrdersEndpoint;
use Kudu\CTKudu\Endpoints\RecipesEndpoint;
use Kudu\CTKudu\Endpoints\ScheduledExportsEndpoint;
use Kudu\CTKudu\Endpoints\TransferredPurchaseOrderEndpoint;
use Kudu\CTKudu\Endpoints\UnitOfMeasureEndpoint;

class CTKuduServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ctkudu.php', 'ctkudu');

//        $this->app->singleton(CrunchTimeClient::class, fn() => new CrunchTimeClient());

        $this->app->singleton(LocationsEndpoint::class);
        $this->app->singleton(UnitOfMeasureEndpoint::class);
        $this->app->singleton(ProductsEndpoint::class);
        $this->app->singleton(CompanyProductsEndpoint::class);
        $this->app->singleton(InventoryEndpoint::class);
        $this->app->singleton(RecipesEndpoint::class);
        $this->app->singleton(PurchaseOrdersEndpoint::class);
        $this->app->singleton(TransferredPurchaseOrderEndpoint::class);
        $this->app->singleton(ScheduledExportsEndpoint::class);
    }

    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/ctkudu.php' => config_path('ctkudu.php'),], 'ctkudu-config');
    }
}
