<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            Store::factory()->create([
                'name' => 'Worten',
                'website_url' => 'https://www.worten.pt/',
                'title_tag' => 'div.product-header__title',
                'price_tag' => 'span.price__numbers--bold.price__numbers.raised-decimal.price__numbers--bold.price__numbers',
                'scratched_price_tag' => 'span.price-container s.price__scratched-price',
                'status_id' => 1,
                'filename' => 'https://www.worten.pt/assetsV4/worten-desktop.DlN-JMO0.svg',
            ]);

            Store::factory()->create([
                'name' => 'Aquario 24',
                'website_url' => 'https://www.aquario.pt/pt/',
                'title_tag' => 'h1.section-title.page-title',
                'price_tag' => 'div.product-detail-price.red',
                'scratched_price_tag' => 'span.pvp-riscado',
                'status_id' => 1,
                'filename' => 'https://www.aquario.pt/interface/imgs/logo-a.webp',
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            echo 'Error! | ';
            Log::error('Store seeder failed: ' . $e);
        }
    }
}
