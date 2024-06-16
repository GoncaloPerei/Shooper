<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            Status::factory()->create([
                'name' => 'Accepted',
            ]);
            Status::factory()->create([
                'name' => 'Pending',
            ]);
            Status::factory()->create([
                'name' => 'Denied',
            ]);

            DB::commit();
            echo 'Success! |';
        } catch (QueryException $e) {
            DB::rollBack();
            echo 'Error! |';
        }
    }
}
