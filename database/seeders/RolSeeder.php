<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::factory()->create(
            ['name' => 'Desarrollador'],
            ['name' => 'Administrador'],
            ['name' => 'Tecnico'],
            ['name' => 'Cajero'],
        );
    }
}
