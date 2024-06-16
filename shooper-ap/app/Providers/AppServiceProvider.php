<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Product;
use App\Observers\ProductObserver;

use App\Models\ProductUrl;
use App\Observers\ProductUrlObserver;

use App\Models\Store;
use App\Observers\StoreObserver;

use App\Models\SearchHistory;
use App\Observers\SearchHistoryObserver;

use App\Models\ProductList;
use App\Observers\ProductListObserver;

use App\Models\PriceAlert;
use App\Observers\PriceAlertObserver;

use App\Models\PriceHistoryProduct;
use App\Observers\PriceHistoryProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        ProductUrl::observe(ProductUrlObserver::class);
        Store::observe(StoreObserver::class);
        SearchHistory::observe(SearchHistoryObserver::class);
        ProductList::observe(ProductListObserver::class);
        PriceAlert::observe(PriceAlertObserver::class);
        PriceHistoryProduct::observe(PriceHistoryProductObserver::class);
    }
}
