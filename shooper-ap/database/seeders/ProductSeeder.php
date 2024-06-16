<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            Product::factory()->create([
                'name' => 'iPhone 12 6.1" 64GB Black',
                'product_brand_id' => '1',
                'product_category_id' => '4',
                'status_id' => '1',
                'created_by' => '1',
                'filename' => 'https://www.aquario.pt/Imgs/produtos/product_197800/374_2.jpg'
            ]);

            Product::factory()->create([
                'name' => 'iPhone 13 6.1" 128GB Midnight',
                'product_brand_id' => '1',
                'product_category_id' => '4',
                'status_id' => '1',
                'created_by' => '1',
                'filename' => 'https://www.aquario.pt/Imgs/produtos/product_282434/MLPF3QL.jpg'
            ]);

            Product::factory()->create([
                'name' => 'iPhone 14 6.1" 128GB Midnight',
                'product_brand_id' => '1',
                'product_category_id' => '4',
                'status_id' => '1',
                'created_by' => '1',
                'filename' => 'https://www.aquario.pt/Imgs/produtos/product_402120/MPUF3QLA.jpg'
            ]);

            Product::factory()->create([
                'name' => 'iPhone 15 6.1" 128GB Black',
                'product_brand_id' => '1',
                'product_category_id' => '4',
                'status_id' => '1',
                'created_by' => '1',
                'filename' => 'https://www.aquario.pt/Imgs/produtos/product_681224/MTP03QL_A.jpg'
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Product Seeder Failed: ' . $e);
            echo 'Error! |';
        }
    }
}
