<?php

namespace App\Observers;

use App\Models\PriceHistoryProduct;

use Illuminate\Support\Facades\Log;

class PriceHistoryProductObserver
{
    /**
     * Handle the PriceHistoryProduct "created" event.
     */
    public function created(PriceHistoryProduct $priceHistoryProduct): void
    {
        
    }

    /**
     * Handle the PriceHistoryProduct "updated" event.
     */
    public function updated(PriceHistoryProduct $priceHistoryProduct): void
    {
        //
    }

    /**
     * Handle the PriceHistoryProduct "deleted" event.
     */
    public function deleted(PriceHistoryProduct $priceHistoryProduct): void
    {
        //
    }

    /**
     * Handle the PriceHistoryProduct "restored" event.
     */
    public function restored(PriceHistoryProduct $priceHistoryProduct): void
    {
        //
    }

    /**
     * Handle the PriceHistoryProduct "force deleted" event.
     */
    public function forceDeleted(PriceHistoryProduct $priceHistoryProduct): void
    {
        //
    }
}
