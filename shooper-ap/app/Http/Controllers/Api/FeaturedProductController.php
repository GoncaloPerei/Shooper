<?php

namespace App\Http\Controllers\Api;

use App\Models\FeaturedProduct;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Resources\FeatureProduct\FeaturedProductResource;

class FeaturedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = FeaturedProduct::first();

        return new FeaturedProductResource($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(FeaturedProduct $featuredProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeaturedProduct $featuredProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeaturedProduct $featuredProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeaturedProduct $featuredProduct)
    {
        //
    }
}
