<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'code' => 'PLAN-1',
            'name' => 'Hosting Tradicional']);
        Category::create([
            'code' => 'PLAN-2',
            'name' => 'Tienda Online']);
        Category::create([
            'code' => 'PLAN-3',
            'name' => 'Servidores VPS']);
    }
}
