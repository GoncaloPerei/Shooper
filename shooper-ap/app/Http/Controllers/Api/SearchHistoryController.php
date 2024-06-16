<?php

namespace App\Http\Controllers\Api;

use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Resources\SearchHistory\SearchHistoryResource;

use App\Http\Requests\SearchHistory\SearchHistoryRequest;

use Illuminate\Support\Facades\DB;

class SearchHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SearchHistoryRequest $request)
    {
        try {
            DB::beginTransaction();

            SearchHistory::create($request->all());

            DB::commit();
            return response()->json(['success' => 'Search History created successfully'], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to create search history', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchHistory $searchHistory)
    {
        try {
            DB::beginTransaction();

            $searchHistory->delete();

            DB::commit();
            return response()->json(['status' => 'Search History deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
