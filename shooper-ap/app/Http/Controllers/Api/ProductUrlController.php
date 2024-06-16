<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductUrl;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProductUrl\StoreProductUrlRequest;
use App\Http\Requests\ProductUrl\UpdateProductUrlRequest;

use App\Http\Resources\ProductUrl\ProductUrlResource;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class ProductUrlController extends Controller
{
    protected $currentRoute;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data =  QueryBuilder::for(ProductUrl::class)
                ->allowedFilters([
                    'name',
                    'status_id',
                    AllowedFilter::trashed()
                ])
                ->latest();

            $dataCount = $data->count();

            return ProductUrlResource::collection($data->paginate((int) $request->paginate))->additional([
                'productUrlsCount' => $dataCount,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product urls', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductUrlRequest $request)
    {
        try {
            DB::beginTransaction();

            ProductUrl::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Product url created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductUrl $url)
    {
        try {
            $data = ProductUrl::with('store', 'status', 'user', 'priceHistoryUrl', 'product')
                ->where('id', $url->id);

            return response()->json(['message' => 'Product Url', 'data' => new ProductUrlResource($data->first())], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductUrlRequest $request, ProductUrl $url)
    {
        try {
            DB::beginTransaction();

            $url->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Product url updated successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['message' => 'Failed to update product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductUrl $url)
    {
        try {
            $url->delete();

            return response()->json(['message' => 'Product url deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to delete product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($productUrl)
    {
        try {
            $data = ProductUrl::onlyTrashed()->findOrFail($productUrl);
            $data->restore();

            return response()->json(['message' => 'Product url restored successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to restore product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function forceDelete($productUrl)
    {
        try {
            $data = ProductUrl::onlyTrashed()->findOrFail($productUrl);
            $data->forceDelete();

            return response()->json(['message' => 'Product url force delete successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to force delete product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
