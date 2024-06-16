<?php

namespace App\Http\Controllers\Api;

use App\Models\PriceHistoryProduct;
use App\Models\PriceHistoryUrl;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PriceHistoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentDate = Carbon::now()->toDateString();

        $data = PriceHistoryUrl::with('productUrl')
            ->get()
            ->groupBy('productUrl.product_id')
            ->map(function ($prices) {
                return [
                    'Avg' => number_format($prices->avg('price'), 2),
                    'Min' => number_format($prices->min('price'), 2)
                ];
            });

        foreach ($data as $productId => $priceData) {
            $existingPriceHistory = PriceHistoryProduct::where('product_id', $productId)
                ->whereDate('created_at', $currentDate)
                ->first();

            try {
                DB::beginTransaction();

                if ($existingPriceHistory) {
                    $existingPriceHistory->update([
                        'avg_price' => $priceData['Avg'],
                        'min_price' => $priceData['Min'],
                    ]);

                    Log::info("Updated Price History for Product ID: {$productId}");
                } else {
                    PriceHistoryProduct::create([
                        'product_id' => $productId,
                        'avg_price' => $priceData['Avg'],
                        'min_price' => $priceData['Min'],
                    ]);

                    Log::info("Created Price History for Product ID: {$productId}");
                }

                DB::commit();
                Log::info("Product ID: {$productId}, Average Price: {$priceData['Avg']}, Minimum Price: {$priceData['Min']}");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error processing Product ID: {$productId} - " . $e->getMessage());
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceHistoryProduct $priceHistoryProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceHistoryProduct $priceHistoryProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceHistoryProduct $priceHistoryProduct)
    {
        //
    }
}
