<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

use App\Classes\AssignVariablesService;

class StoreObserver
{
    protected $assignVariablesService;

    public function __construct(AssignVariablesService $assignVariablesService)
    {
        $this->assignVariablesService = $assignVariablesService;
    }
    /**
     * Handle the Store "created" event.
     */
    public function created(Store $store): void
    {
        try {
            $this->assignVariablesService->assignStatus($store);

            $store->saveQuietly();
        } catch (QueryException $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the Store "updated" event.
     */
    public function updated(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "deleted" event.
     */
    public function deleted(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "restored" event.
     */
    public function restored(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "force deleted" event.
     */
    public function forceDeleted(Store $store): void
    {
        //
    }
}
