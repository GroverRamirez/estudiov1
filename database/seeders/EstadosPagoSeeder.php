<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoPago;

class EstadosPagoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Pendiente'],
            ['nombre' => 'Parcial'],
            ['nombre' => 'Completado'],
            ['nombre' => 'Cancelado'],
        ];

        foreach ($estados as $estado) {
            EstadoPago::create($estado);
        }
    }
}
