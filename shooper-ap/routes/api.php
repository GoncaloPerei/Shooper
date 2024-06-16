<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductUrlController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\PriceHistoryProductController;
use App\Http\Controllers\Api\FeaturedProductController;
use App\Http\Controllers\Api\PriceAlertController;
use App\Http\Controllers\Api\ProductBrandController;
use App\Http\Controllers\Api\ProductListController;
use App\Http\Controllers\Api\SearchHistoryController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Api\RoleController;

//Authentication
Route::prefix('auth')->group(function () {
    //SignIn Route
    Route::post('signin', [AuthController::class, 'signin']);

    //SignUp Route
    Route::post('signup', [AuthController::class, 'signup']);

    Route::middleware('auth:sanctum')->group(function () {
        //SignOut Route
        Route::post('signout', [AuthController::class, 'signout']);
        //Check Auth User Route
        Route::get('user', [AuthController::class, 'user']);
    });
});

//Users
Route::apiResource('users', UserController::class);

//Status
Route::get('status', [StatusController::class, 'index']);

//Product
Route::apiResource('products', ProductController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    //Check if product is favorite Route
    Route::post('product/is-favorite', [AuthController::class, 'isFavourite']);
    //Check if product has alert Route
    Route::post('product/has-alert', [AuthController::class, 'hasAlert']);
});

//Product Brand
Route::apiResource('product/brand', ProductBrandController::class);

//Product Url
Route::apiResource('product/url', ProductUrlController::class);
Route::post('product/url', [ProductUrlController::class, 'store'])->middleware('auth:sanctum');

//Product Category
Route::apiResource('product/category', ProductCategoryController::class);

//Product Lists
Route::apiResource('product/list', ProductListController::class);
Route::middleware('auth:sanctum')->group(function () {
    //Add product to a specific list Route
    Route::post('product/list/{listId}/add-product', [ProductListController::class, 'addProducts']);
    //Remove product from a specific list Route
    Route::post('product/list/{listId}/remove-product', [ProductListController::class, 'removeProducts']);
});

//Featured Product
Route::get('featured-product', [FeaturedProductController::class, 'index']);

//Stores
Route::apiResource('stores', StoreController::class);
Route::post('stores', [StoreController::class, 'store'])->middleware('auth:sanctum');

//Search History
Route::apiResource('search-history', SearchHistoryController::class);
Route::post('search-history', [SearchHistoryController::class, 'store'])->middleware('auth:sanctum');

//Price Alert
Route::apiResource('price-alert', PriceAlertController::class)->middleware('auth:sanctum');

//Restore data from CRUD Route
Route::prefix('restore')->group(function () {
    Route::post('users/{user}', [UserController::class, 'restore']);
    Route::post('products/{id}', [ProductController::class, 'restore']);
    Route::post('product/url/{productUrl}', [ProductUrlController::class, 'restore']);
    Route::post('product/category/{category}', [ProductCategoryController::class, 'restore']);
    Route::post('product/brand/{brand}', [ProductBrandController::class, 'restore']);
    Route::post('stores/{store}', [StoreController::class, 'restore']);
});

//Force Delete data from CRUD Route
Route::prefix('force-delete')->group(function () {
    Route::post('users/{user}', [UserController::class, 'forceDelete']);
    Route::post('products/{product}', [ProductController::class, 'forceDelete']);
    Route::post('product/url/{productUrl}', [ProductUrlController::class, 'forceDelete']);
    Route::post('product/category/{category}', [ProductCategoryController::class, 'forceDelete']);
    Route::post('product/brand/{brand}', [ProductBrandController::class, 'forceDelete']);
    Route::post('stores/{store}', [StoreController::class, 'forceDelete']);
});



// Route::apiResource('roles', RoleController::class);

// Route::prefix('customer')->group(function () {
//     Route::get('product/category', [ProductCategoryController::class, 'index']);
//     Route::get('featured-product', [FeaturedProductController::class, 'index']);
// });
