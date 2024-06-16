<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Resources\ProductBrand\ProductBrandResource;

use App\Http\Requests\ProductBrand\StoreProductBrandRequest;
use App\Http\Requests\ProductBrand\UpdateProductBrandRequest;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ProductBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = QueryBuilder::for(ProductBrand::class)
                ->allowedFilters([
                    AllowedFilter::trashed()
                ])
                ->latest();

            $dataCount = $data->count();

            return ProductBrandResource::collection($data->paginate((int) $request->paginate))->additional([
                'productBrandsCount' => $dataCount,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product brands', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductBrandRequest $request)
    {
        try {
            DB::beginTransaction();

            ProductBrand::create($request->all());

            if ($request->has('brands')) {
                foreach ($request->brands as $brand) {
                    $brand->product()->attach($brand->brand_id);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Product brand created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create product brand', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductBrand $brand)
    {
        try {
            $data = ProductBrand::where('id', $brand->id);

            return response()->json(['message' => 'Product Brand', 'data' => new ProductBrandResource($data->first())], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product brand', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductBrandRequest $request, ProductBrand $brand)
    {
        try {
            DB::beginTransaction();

            $brand->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Product brand updated successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to update product brand', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductBrand $brand)
    {
        try {
            $brand->delete();

            return response()->json(['message' => 'Product brand deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to delete product brand', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($brand)
    {
        try {
            $data = ProductBrand::onlyTrashed()->findOrFail($brand);
            $data->restore();

            return response()->json(['message' => 'Product brand restored successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to restore product brand', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function forceDelete($brand)
    {
        try {
            $data = ProductBrand::onlyTrashed()->findOrFail($brand);
            $data->forceDelete();

            return response()->json(['message' => 'Product brand force delete successfully'], 200);
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to force delete product brand', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
