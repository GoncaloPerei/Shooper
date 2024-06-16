<?php

namespace App\Observers;

use App\Models\ProductList;

use App\Classes\AssignVariablesService;
use Illuminate\Validation\ValidationException;

class ProductListObserver
{
    protected $assignVariablesService;

    public function __construct(AssignVariablesService $assignVariablesService)
    {
        $this->assignVariablesService = $assignVariablesService;
    }

    /**
     * Handle the ProductList "created" event.
     */
    public function created(ProductList $productList): void
    {
        try {
            $this->assignVariablesService->assignCreatedBy($productList);

            $productList->saveQuietly();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the ProductList "updated" event.
     */
    public function updated(ProductList $productList): void
    {
        //
    }

    /**
     * Handle the ProductList "deleted" event.
     */
    public function deleted(ProductList $productList): void
    {
        //
    }

    /**
     * Handle the ProductList "restored" event.
     */
    public function restored(ProductList $productList): void
    {
        //
    }

    /**
     * Handle the ProductList "force deleted" event.
     */
    public function forceDeleted(ProductList $productList): void
    {
        //
    }
}
