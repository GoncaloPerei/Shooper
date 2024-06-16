<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

use App\Http\Resources\Product\ProductResource;

use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class ProductController extends Controller
{
    protected $currentRoute;

    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->currentRoute = $request->route()->uri();

        try {
            $data = QueryBuilder::for(Product::class)
                ->with('productCategory', 'status', 'productUrl', 'user', 'priceHistory', 'brand')
                ->allowedFilters([
                    'name',
                    'status_id',
                    'product_brand_id',
                    'product_category_id',
                    AllowedFilter::scope('price_range'),
                    AllowedFilter::trashed(),
                ])
                ->latest();

            $dataCount = $data->count();

            return ProductResource::collection($data->paginate((int) $request->paginate))->additional([
                'productCount' => $dataCount,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show products', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            Product::create($request->all());

            DB::commit();
            return response()->json(['success' => 'Product created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $data = Product::with('productCategory', 'status', 'productUrl', 'user', 'priceHistory')
                ->withCount(['productUrl as productUrl_count'])
                ->where('id', $product->id);

            return response()->json(['message' => 'Product', 'data' => new ProductResource($data->first())], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Product updated successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            $product->delete();

            DB::commit();
            return response()->json(['status' => 'Product deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($product)
    {
        try {
            DB::beginTransaction();

            $data = Product::onlyTrashed()->findOrFail($product);
            $data->restore();

            DB::commit();
            return response()->json(['status' => 'Product restored successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function forceDelete($product)
    {
        try {
            $data = Product::onlyTrashed()->findOrFail($product);
            $data->forceDelete();

            return response()->json(['message' => 'Product force delete successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to force delete product', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
