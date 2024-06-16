<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

use App\Classes\AssignVariablesService;

use Illuminate\Support\Facades\Log;

class ProductObserver
{
    protected $assignVariablesService;

    public function __construct(AssignVariablesService $assignVariablesService)
    {
        $this->assignVariablesService = $assignVariablesService;
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        try {
            $this->assignVariablesService->assignStatus($product);

            $this->assignVariablesService->assignCreatedBy($product);

            $product->saveQuietly();
        } catch (QueryException $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
