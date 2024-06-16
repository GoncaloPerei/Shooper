<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;

class RoleController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roleData = Role::orderBy('id', 'desc');

            return RoleResource::collection($roleData->get());
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to show user roles', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
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
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
