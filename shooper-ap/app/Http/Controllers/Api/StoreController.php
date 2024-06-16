<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Resources\Store\StoreResource;

use App\Http\Requests\Store\StoreStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;

use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StoreController extends Controller
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
            $data = QueryBuilder::for(Store::class)
                ->with('status')
                ->allowedFilters([
                    'name',
                    'status_id',
                    AllowedFilter::trashed()
                ])
                ->latest();

            $dataCount = $data->count();

            return StoreResource::collection($data->paginate((int) $request->paginate))->additional([
                'storesCount' => $dataCount,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show stores', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            Store::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Store created successfully'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create store', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        try {
            $data = Store::where('id', $store->id)
                ->first();

            return response()->json(['message' => 'Store', 'data' => new StoreResource($data)], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show store', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        try {
            DB::beginTransaction();

            $store->update($request->all());

            DB::commit();
            return response()->json(['message' => 'Store updated successfully!'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update store', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        try {
            DB::beginTransaction();

            $store->delete();

            DB::commit();
            return response()->json(['message' => 'Store deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete store', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($store)
    {
        try {
            DB::beginTransaction();

            $data = Store::onlyTrashed()->findOrFail($store);
            $data->restore();

            DB::commit();
            return response()->json(['message' => 'Store restored successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to restore store', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    public function forceDelete($store)
    {
        try {
            DB::beginTransaction();

            $data = Store::onlyTrashed()->findOrFail($store);
            $data->forceDelete();

            DB::commit();
            return response()->json(['message' => 'Store restored successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to restore store', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
