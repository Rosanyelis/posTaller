<?php

namespace Database\Seeders;

use App\Models\TypeProduct;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeProduct::create(['name' => 'Servicios']);
        TypeProduct::create(['name' => 'Productos']);
    }
}
