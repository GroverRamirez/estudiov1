<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador'],
            ['nombre' => 'Empleado'],
            ['nombre' => 'Cliente'],
        ];

        foreach ($roles as $role) {
            Rol::create($role);
        }
    }
}
