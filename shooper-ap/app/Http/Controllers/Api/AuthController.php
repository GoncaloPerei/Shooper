<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\StoreAuthRequest;
use App\Http\Requests\Auth\StoreLoginRequest;

use App\Http\Resources\ProductList\ProductListResource;

use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

use Illuminate\Support\Facades\Log;

use App\Http\Resources\User\UserResource;

class AuthController extends Controller
{
    protected $userController;
    protected $user;

    public function __construct(UserController $otherController)
    {
        $this->user = Auth::user();
        $this->userController = $otherController;
    }

    public function user()
    {
        $user = QueryBuilder::for(User::class)
            ->allowedIncludes(['productList', 'role', 'searchHistory'])
            ->where('id', $this->user->id)
            ->first();

        return response()->json(['message' => 'User', 'data' => new UserResource($user)], 200);
    }

    public function isFavourite(Request $request)
    {
        $user = User::where('id', $this->user->id)
            ->first();

        $productList = $user->productList()
            ->whereHas('products', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->first();

        $isFavourite = !is_null($productList);

        if ($isFavourite) {
            return response()->json(['data' => TRUE, 'list' => new ProductListResource($productList)]);
        }

        return response()->json(['data' => FALSE]);
    }

    public function hasAlert(Request $request)
    {
        $user = User::where('id', $this->user->id)
            ->first();

        $hasAlert = $user->priceAlert()
            ->where('product_id', $request->product_id)
            ->first();

        if ($hasAlert) {
            return response()->json(['data' => TRUE, 'alert' => $hasAlert]);
        }

        return response()->json(['data' => FALSE]);
    }

    public function signin(StoreLoginRequest $request)
    {
        try {
            if (Cookie::get('jwt')) {
                return response()->json(['error' => 'User is already authenticated'], 400);
            }

            if (!Auth::attempt($request->validated())) {
                return response()->json(['error' => 'Email or password are wrong'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken("token")->plainTextToken;
            $cookie = Cookie::make("jwt", $token, 60 * 24);

            return response()->json([
                "message" => "Sign in successfully"
            ], 200)->withCookie($cookie);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to sign in user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function signup(StoreAuthRequest $request)
    {
        try {
            User::create($request->all());

            return response()->json(['message' => 'User sign up successfully'], 201);
        } catch (QueryException $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => 'Failed to sign up user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function signout()
    {
        try {
            $cookie = Cookie::forget("jwt");

            return response()->json([
                'message' => 'Sign out successfully'
            ], 200)->withCookie($cookie);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to sign out user', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
