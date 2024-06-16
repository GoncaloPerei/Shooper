<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductList;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use App\Http\Resources\ProductList\ProductListResource;

use App\Http\Requests\ProductList\StoreProductListRequest;
use App\Http\Requests\ProductList\UpdateProductListRequest;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class ProductListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(ProductList::class)
            ->with('products')
            ->allowedIncludes(['users'])
            ->latest();

        $dataCount = $data->count();

        return ProductListResource::collection($data->paginate((int) $request->paginate))->additional([
            'productListsCount' => $dataCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductListRequest $request)
    {
        try {
            DB::beginTransaction();

            ProductList::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Product list created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error(['message' => 'Failed to create product list', 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create product url', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(['message' => 'An error ocurred', 'error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function addProducts(Request $request, $listId)
    {
        try {
            DB::beginTransaction();

            $list = ProductList::findOrFail($listId);

            if ($request->has('product_id')) {
                $list->products()->attach($request->product_id);
            }

            DB::commit();
            return response()->json(['message' => 'Products added to list successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error(['message' => 'Failed to add products to list', 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to add products to list', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(['message' => 'An error occurred', 'error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }


    public function removeProducts(Request $request, $listId)
    {
        try {
            DB::beginTransaction();

            $list = ProductList::findOrFail($listId);

            if ($request->has('product_id')) {
                $list->products()->detach($request->product_id);
            }

            DB::commit();
            return response()->json(['message' => 'Products removed from list successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error(['message' => 'Failed to remove products from list', 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to remove products from list', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(['message' => 'An error occurred', 'error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductList $list)
    {
        $data = QueryBuilder::for(ProductList::class)
            ->where('id', $list->id)
            ->with('products')
            ->withCount('products')
            ->allowedIncludes(['user'])
            ->firstOrFail();

        return new ProductListResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductListRequest $request, ProductList $list)
    {
        try {
            DB::beginTransaction();

            $list->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Product list updated successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['message' => 'Failed to update product list', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductList $list)
    {
        try {
            $list->delete();

            return response()->json(['message' => 'Product list deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to delete product list', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
