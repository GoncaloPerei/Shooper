<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProductCategorySeeder::class,
            StatusSeeder::class,
            StoreSeeder::class,
            ProductBrandSeeder::class,
            ProductSeeder::class,
            ProductUrlSeeder::class,
            FeatureProductSeeder::class,
        ]);
    }
}
