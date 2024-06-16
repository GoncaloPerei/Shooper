<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\ProductUrl;
use Illuminate\Support\Facades\Log;

class ProductUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            ProductUrl::factory()->create([
                'url' => 'https://www.worten.pt/produtos/iphone-12-apple-6-1-64-gb-preto-7256276',
                'product_id' => '1',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.aquario.pt/pt/product/apple-smartphone-apple-iphone-12-61-64gb-preto-mgj53ql-a',
                'product_id' => '1',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.worten.pt/produtos/iphone-13-apple-6-1-128-gb-meia-noite-7461513',
                'product_id' => '2',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.aquario.pt/pt/product/apple-smartphone-apple-iphone-13-61-128gb-meia-noite-mlpf3ql-a',
                'product_id' => '2',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.worten.pt/produtos/iphone-14-apple-6-1-128-gb-meia-noite-7644302',
                'product_id' => '3',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.aquario.pt/pt/product/apple-smartphone-apple-iphone-14-128gb-midnight-mpuf3ql-a',
                'product_id' => '3',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.aquario.pt/pt/product/apple-smartphone-apple-iphone-15-61-128gb-preto-mtp03ql-a',
                'product_id' => '4',
                'created_by' => '1',
            ]);

            ProductUrl::factory()->create([
                'url' => 'https://www.worten.pt/produtos/apple-iphone-15-6-1-128-gb-preto-7851120',
                'product_id' => '4',
                'created_by' => '1',
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Product Url Seeder Failed: ' . $e);
            echo 'Error! |';
        }
    }
}
