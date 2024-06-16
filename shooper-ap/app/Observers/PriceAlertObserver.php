<?php

namespace App\Observers;

use App\Models\PriceAlert;

use Illuminate\Validation\ValidationException;

use App\Classes\AssignVariablesService;

class PriceAlertObserver
{
    protected $assignVariablesService;

    public function __construct(AssignVariablesService $assignVariablesService)
    {
        $this->assignVariablesService = $assignVariablesService;
    }

    /**
     * Handle the PriceAlert "created" event.
     */
    public function created(PriceAlert $priceAlert): void
    {
        try {
            $this->assignVariablesService->assignCreatedBy($priceAlert);

            $priceAlert->saveQuietly();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the PriceAlert "updated" event.
     */
    public function updated(PriceAlert $priceAlert): void
    {
        //
    }

    /**
     * Handle the PriceAlert "deleted" event.
     */
    public function deleted(PriceAlert $priceAlert): void
    {
        //
    }

    /**
     * Handle the PriceAlert "restored" event.
     */
    public function restored(PriceAlert $priceAlert): void
    {
        //
    }

    /**
     * Handle the PriceAlert "force deleted" event.
     */
    public function forceDeleted(PriceAlert $priceAlert): void
    {
        //
    }
}
