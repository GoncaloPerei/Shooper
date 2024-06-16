<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

use App\Models\FeaturedProduct;
use Illuminate\Support\Facades\Log;

class FeatureProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            FeaturedProduct::factory()->create([
                'product_id' => '1'
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Feature Product Seeder Failed: ' . $e);
            echo 'Error! |';
        }
    }
}
