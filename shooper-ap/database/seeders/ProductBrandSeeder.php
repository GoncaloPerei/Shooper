<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\ProductBrand;
use Illuminate\Support\Facades\Log;

class ProductBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            ProductBrand::factory()->create([
                'name' => 'Apple',
            ]);
            ProductBrand::factory()->create([
                'name' => 'Samsung',
            ]);
            ProductBrand::factory()->create([
                'name' => 'Xiaomi',
            ]);
            ProductBrand::factory()->create([
                'name' => 'Honor',
            ]);
            ProductBrand::factory()->create([
                'name' => 'OnePlus',
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Product Brand Seeder Failed: ' . $e);
            echo 'Error! |';
        }
    }
}
