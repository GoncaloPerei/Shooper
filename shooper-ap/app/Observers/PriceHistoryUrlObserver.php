<?php

namespace App\Observers;

use App\Models\PriceHistoryUrl;

class PriceHistoryUrlObserver
{
    /**
     * Handle the PriceHistoryUrl "created" event.
     */
    public function created(PriceHistoryUrl $priceHistoryUrl): void
    {
        //
    }

    /**
     * Handle the PriceHistoryUrl "updated" event.
     */
    public function updated(PriceHistoryUrl $priceHistoryUrl): void
    {
        //
    }

    /**
     * Handle the PriceHistoryUrl "deleted" event.
     */
    public function deleted(PriceHistoryUrl $priceHistoryUrl): void
    {
        //
    }

    /**
     * Handle the PriceHistoryUrl "restored" event.
     */
    public function restored(PriceHistoryUrl $priceHistoryUrl): void
    {
        //
    }

    /**
     * Handle the PriceHistoryUrl "force deleted" event.
     */
    public function forceDeleted(PriceHistoryUrl $priceHistoryUrl): void
    {
        //
    }
}
