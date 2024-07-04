<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()->create(
            ['name' => 'Tienda 1',
            'code' => '001',
            'phone' => '123456789',
            'address' => 'Calle 1',],
        );
    }
}
