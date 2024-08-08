<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\TypeProduct;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category_id = Category::where('code', 'PLAN-1')->first()->id;
        $type = TypeProduct::where('name', 'Servicios')->first()->name;
        Product::create([
            'code' => 'S-0001',
            'name' => 'Plan Pyme',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 70000,
            'cost' => 70000,
            'description' => '5 GB Espacio, Correos Ilimitados, Navegación Ilimitada, SSL, CPanel',
        ]);
        Product::create([
            'code' => 'S-0002',
            'name' => 'Plan Pro',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 90000,
            'cost' => 90000,
            'description' => '10 GB Espacio, Correos Ilimitados, Navegación Ilimitada, SSL, CPanel',
        ]);
        Product::create([
            'code' => 'S-0003',
            'name' => 'Plan Advanced',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 120000,
            'cost' => 120000,
            'description' => '20 GB Espacio, Correos Ilimitados, Navegación Ilimitada, SSL, CPanel',
        ]);

        $category_id = Category::where('code', 'PLAN-2')->first()->id;
        Product::create([
            'code' => 'S-0004',
            'name' => 'Plan Inicia',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 120000,
            'cost' => 120000,
            'description' => '10 GB Espacio, Correos Ilimitados, Navegación Ilimitada, SSL, CPanel',
        ]);
        Product::create([
            'code' => 'S-0005',
            'name' => 'Plan Pro',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 230000,
            'cost' => 230000,
            'description' => '20 GB Espacio, Correos Ilimitados, Navegación Ilimitada, SSL, CPanel',
        ]);
        Product::create([
            'code' => 'S-0006',
            'name' => 'Plan Advanced',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 330000,
            'cost' => 330000,
            'description' => '40 GB Espacio, Correos Ilimitados, Navegación Ilimitada, SSL, CPanel',
        ]);

        $category_id = Category::where('code', 'PLAN-3')->first()->id;
        Product::create([
            'code' => 'S-0007',
            'name' => 'Plan Inicia',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 900000,
            'cost' => 90000,
            'description' => '25 GB Espacio, 2 GB Ram, 2 Nucleos, Navegación Ilimitada, SSL, IP Dedicada',
        ]);
        Product::create([
            'code' => 'S-0008',
            'name' => 'Plan Pro',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 104000,
            'cost' => 104000,
            'description' => '50 GB Espacio, 4 GB Ram, 2 Nucleos, Navegación Ilimitada, SSL, IP Dedicada',
        ]);
        Product::create([
            'code' => 'S-0009',
            'name' => 'Plan Advanced',
            'category_id' => $category_id,
            'type' => $type,
            'quantity' => 1000,
            'price' => 115000,
            'cost' => 115000,
            'description' => '100 GB Espacio, 8 GB Ram, 4 Nucleos, Navegación Ilimitada, SSL, IP Dedicada',
        ]);
    }
}
