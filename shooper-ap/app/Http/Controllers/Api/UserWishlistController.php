<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class UserWishlistController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
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
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($request->product_id);

            UserWishlist::create(['user_id' => $this->user->id, 'product_id' => $product->id]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to remove favorite', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserWishlist $userWishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserWishlist $userWishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::where('id', $this->user->id)
                ->firstOrFail();

            $user->wishlist()->detach($request->product_id);

            DB::commit();
            return response()->json(['message' => 'Favorite removed successfully'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to remove favorite', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
