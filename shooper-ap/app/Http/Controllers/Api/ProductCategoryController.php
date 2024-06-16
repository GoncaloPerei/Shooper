<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\ProductCategory\UpdateProductCategoryRequest;

use App\Http\Resources\ProductCategory\ProductCategoryResource;
use App\Http\Resources\ProductCategory\ProductCategoryCollection;

use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class ProductCategoryController extends Controller
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
            $data = QueryBuilder::for(ProductCategory::class)
                ->allowedIncludes(['product', 'productUrl', 'productBrand'])
                ->allowedFilters([
                    'name',
                    'min_price',
                    'product.status_id',
                    'productUrl.status_id',
                    AllowedFilter::trashed(),
                ])
                ->latest();

            $dataCount = $data->count();

            return ProductCategoryResource::collection($data->paginate((int) $request->paginate))->additional([
                'categoriesCount' => $dataCount,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product categories', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->file('image')->store('images', 'public/images');
            DB::beginTransaction();

            // if ($request->hasFile('filename')) {
            //     $file = $request->file('filename');
            //     $fileName = $file->getClientOriginalName();
            //     $filePath = $file->storeAs('images/product_categories', $fileName);

            //     $data['filename'] = $filePath;
            // }

            DB::commit();
            return response()->json(['success' => 'Category created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create product category', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $category)
    {
        try {
            $data = ProductCategory::where('id', $category->id)
                ->first();

            return response()->json(['message' => 'Product Category', 'data' => new ProductCategoryResource($data)], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show product category', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $category)
    {
        try {
            DB::beginTransaction();

            $category->update($request->all());

            DB::commit();
            return response()->json(['success' => 'Product category updated successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product category', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $category)
    {
        try {
            DB::beginTransaction();

            $category->delete();

            DB::commit();
            return response()->json(['status' => 'Product category deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete product category', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($category)
    {
        try {
            DB::beginTransaction();

            $data = ProductCategory::onlyTrashed()->findOrFail($category);
            $data->restore();

            DB::commit();
            return response()->json(['status' => 'Product category restored successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to restore product category', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function forceDelete($category)
    {
        try {
            DB::beginTransaction();

            $data = ProductCategory::onlyTrashed()->findOrFail($category);
            $data->forceDelete();

            DB::commit();
            return response()->json(['status' => 'Product category force deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to force delete product category', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
