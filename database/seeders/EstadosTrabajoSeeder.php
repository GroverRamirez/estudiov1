<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoTrabajo;

class EstadosTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Pendiente'],
            ['nombre' => 'En Proceso'],
            ['nombre' => 'Completado'],
            ['nombre' => 'Cancelado'],
        ];

        foreach ($estados as $estado) {
            EstadoTrabajo::create($estado);
        }
    }
}
