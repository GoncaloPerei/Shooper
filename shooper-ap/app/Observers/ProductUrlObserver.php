<?php

namespace App\Observers;

use App\Models\ProductUrl;
use App\Models\Store;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Api\PriceHistoryUrlController;

use App\Classes\AssignVariablesService;
use App\Classes\ScrapeProduct;

class ProductUrlObserver
{

    protected $scrapeProduct;
    protected $assignVariablesService;
    protected $priceHistory;

    public function __construct(AssignVariablesService $assignVariablesService, PriceHistoryUrlController $priceHistory, ScrapeProduct $scrapeProduct)
    {
        $this->assignVariablesService = $assignVariablesService;
        $this->priceHistory = $priceHistory;
        $this->scrapeProduct = $scrapeProduct;
    }

    /**
     * Handle the ProductUrl "created" event.
     */
    public function created(ProductUrl $productUrl): void
    {
        try {
            $this->assignVariablesService->assignStatus($productUrl);

            $this->assignVariablesService->assignCreatedBy($productUrl);

            $this->assignVariablesService->assignStore(Store::class, $productUrl);

            $productUrl->saveQuietly();

            $this->scrapeProduct->scrapeProduct($productUrl->url);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the ProductUrl "updated" event.
     */
    public function updated(ProductUrl $productUrl): void
    {
        try {
            $dataToStore = [
                'product_url_id' => $productUrl->id,
                'price' => $productUrl->price,
            ];
            $this->priceHistory->index(new Request($dataToStore));
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the ProductUrl "deleted" event.
     */
    public function deleted(ProductUrl $productUrl): void
    {
        //
    }

    /**
     * Handle the ProductUrl "restored" event.
     */
    public function restored(ProductUrl $productUrl): void
    {
        //
    }

    /**
     * Handle the ProductUrl "force deleted" event.
     */
    public function forceDeleted(ProductUrl $productUrl): void
    {
        //
    }
}
