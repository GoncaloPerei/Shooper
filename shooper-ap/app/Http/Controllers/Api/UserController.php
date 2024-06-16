<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = QueryBuilder::for(User::class)
                ->where('id', '<>', 1)
                ->allowedFilters([
                    'name',
                    'role_id',
                    AllowedFilter::trashed(),
                ])
                ->latest();

            $dataCount = $data->count();

            return UserResource::collection($data->paginate((int) $request->paginate))->additional([
                'usersCount' => $dataCount,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show users', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            User::create($request->all());

            DB::commit();
            return response()->json(['message' => 'User created successfully!'], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = User::where('id', $user->id);

            return response()->json(['message' => 'User', 'data' => new UserResource($data->first())], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $user->update($request->all());

            DB::commit();
            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($user)
    {
        try {
            DB::beginTransaction();

            $data = User::onlyTrashed()->findOrFail($user);
            $data->restore();

            DB::commit();
            return response()->json(['message' => 'User restored successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function forceDelete($user)
    {
        try {
            DB::beginTransaction();

            $data = User::onlyTrashed()->findOrFail($user);
            $data->forceDelete();

            DB::commit();
            return response()->json(['status' => 'User force deleted successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to force delete user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
