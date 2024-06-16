<?php

namespace App\Http\Controllers\Api;

use App\Models\PriceAlert;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Requests\PriceAlert\StorePriceAlertRequest;

use Illuminate\Support\Facades\DB;

class PriceAlertController extends Controller
{
    public function __construct()
    {
        //
    }

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
    public function store(StorePriceAlertRequest $request)
    {
        $data = PriceAlert::where('product_id', $request->product_id)
            ->where('created_by', auth()->user()->id)
            ->first();

        if ($data) {
            return response()->json(['error' => 'An error occurred'], 500);
        }

        try {
            DB::beginTransaction();

            PriceAlert::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Price alert created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create price alert', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceAlert $priceAlert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceAlert $priceAlert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceAlert $price_alert)
    {
        try {
            $price_alert->delete();

            return response()->json(['message' => 'Price alert deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to delete price alert', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
