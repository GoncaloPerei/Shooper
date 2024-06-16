<?php

namespace App\Observers;

use App\Models\SearchHistory;
use Illuminate\Validation\ValidationException;

use App\Classes\AssignVariablesService;

class SearchHistoryObserver
{
    protected $assignVariablesService;

    public function __construct(AssignVariablesService $assignVariablesService)
    {
        $this->assignVariablesService = $assignVariablesService;
    }

    /**
     * Handle the SearchHistory "created" event.
     */
    public function created(SearchHistory $searchHistory): void
    {
        try {
            $this->assignVariablesService->assignCreatedBy($searchHistory);

            $searchHistory->saveQuietly();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }

    /**
     * Handle the SearchHistory "updated" event.
     */
    public function updated(SearchHistory $searchHistory): void
    {
        //
    }

    /**
     * Handle the SearchHistory "deleted" event.
     */
    public function deleted(SearchHistory $searchHistory): void
    {
        //
    }

    /**
     * Handle the SearchHistory "restored" event.
     */
    public function restored(SearchHistory $searchHistory): void
    {
        //
    }

    /**
     * Handle the SearchHistory "force deleted" event.
     */
    public function forceDeleted(SearchHistory $searchHistory): void
    {
        //
    }
}
