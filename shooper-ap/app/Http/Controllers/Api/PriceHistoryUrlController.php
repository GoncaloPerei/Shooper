<?php

namespace App\Http\Controllers\Api;

use App\Models\PriceHistoryUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PriceHistoryUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentDate = Carbon::now()->toDateString();
        try {
            DB::beginTransaction();

            $data = PriceHistoryUrl::where('product_url_id', $request->product_url_id)
                ->whereDate('created_at', $currentDate)
                ->first();

            if ($data) {
                $data->update($request->all());
            } else {
                PriceHistoryUrl::create($request->all());
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("Failed to create product url: " . $e->getMessage());
            return response()->json(['message' => 'Failed to create product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("An error occurred: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceHistoryUrl $priceHistoryUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceHistoryUrl $priceHistoryUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceHistoryUrl $priceHistoryUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceHistoryUrl $priceHistoryUrl)
    {
        //
    }
}
