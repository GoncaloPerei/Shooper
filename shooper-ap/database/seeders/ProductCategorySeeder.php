<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Log;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            ProductCategory::factory()->create([
                'name' => 'Health and Beauty',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-9.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Electronics',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-4.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Computing',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-1.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Smartphones and Accessories',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-2.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Image and Sound',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-3.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Gaming',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-10.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Pets',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-12.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'House and Decoration',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-8.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'DIY and Construction',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-18.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Childcare and Toys',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-6.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Sports',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-7.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Auto and Moto',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-16.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Fashion and Accessories',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-5.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Office and Stationery',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-15.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Culture and Leisure',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-13.png'
            ]);
            ProductCategory::factory()->create([
                'name' => 'Gastronomy and Wines',
                'filename' => 'https://s1.kuantokusta.pt/img_upload/Repository/site/categories/f-17.png'
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Product Category Seeder Failed: ' . $e);
            echo 'Error! |';
        }
    }
}
