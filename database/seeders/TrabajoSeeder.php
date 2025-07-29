<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\User;

class TrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have users, clients and services
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $clientes = Cliente::all();
        $servicios = Servicio::all();

        if ($clientes->isEmpty() || $servicios->isEmpty()) {
            $this->command->info('No hay clientes o servicios disponibles. Ejecuta primero ClienteSeeder y ServicioSeeder.');
            return;
        }

        $trabajos = [
            [
                'titulo' => 'Sesión de Fotos para Boda',
                'descripcion' => 'Sesión fotográfica completa para boda de María y Juan. Incluye ceremonia, recepción y sesión de pareja.',
                'fecha_inicio' => now()->subDays(5),
                'fecha_entrega' => now()->addDays(10),
                'estado' => 'en_proceso',
                'prioridad' => 'alta',
                'precio_total' => 800.00,
                'adelanto' => 400.00,
                'observaciones' => 'Cliente solicita fotos en formato digital y álbum físico.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'fotografia')->first()->idServicio,
            ],
            [
                'titulo' => 'Video Corporativo Empresa ABC',
                'descripcion' => 'Producción de video corporativo para presentación de productos y servicios de la empresa.',
                'fecha_inicio' => now()->subDays(10),
                'fecha_entrega' => now()->addDays(5),
                'estado' => 'pendiente',
                'prioridad' => 'media',
                'precio_total' => 1200.00,
                'adelanto' => 600.00,
                'observaciones' => 'Incluir testimonios de clientes y estadísticas de la empresa.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'video')->first()->idServicio,
            ],
            [
                'titulo' => 'Edición de Fotos de Producto',
                'descripcion' => 'Edición profesional de 50 fotos de productos para catálogo online.',
                'fecha_inicio' => now()->subDays(3),
                'fecha_entrega' => now()->addDays(2),
                'estado' => 'completado',
                'prioridad' => 'baja',
                'precio_total' => 300.00,
                'adelanto' => 300.00,
                'observaciones' => 'Fotos ya entregadas al cliente.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'edicion')->first()->idServicio,
            ],
            [
                'titulo' => 'Cobertura de Evento Empresarial',
                'descripcion' => 'Cobertura fotográfica completa del evento anual de la empresa XYZ.',
                'fecha_inicio' => now()->addDays(5),
                'fecha_entrega' => now()->addDays(15),
                'estado' => 'pendiente',
                'prioridad' => 'urgente',
                'precio_total' => 600.00,
                'adelanto' => 200.00,
                'observaciones' => 'Evento de 8 horas, incluir fotos grupales y momentos clave.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'eventos')->first()->idServicio,
            ],
            [
                'titulo' => 'Impresión de Fotos Familiares',
                'descripcion' => 'Impresión de alta calidad de 30 fotos familiares en diferentes tamaños.',
                'fecha_inicio' => now()->subDays(1),
                'fecha_entrega' => now()->addDays(1),
                'estado' => 'en_proceso',
                'prioridad' => 'media',
                'precio_total' => 150.00,
                'adelanto' => 75.00,
                'observaciones' => 'Papel fotográfico mate, tamaños 4x6 y 5x7.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'impresion')->first()->idServicio,
            ],
            [
                'titulo' => 'Video Musical para Artista Local',
                'descripcion' => 'Producción de video musical con edición creativa y efectos especiales.',
                'fecha_inicio' => now()->subDays(15),
                'fecha_entrega' => now()->addDays(20),
                'estado' => 'en_proceso',
                'prioridad' => 'alta',
                'precio_total' => 1500.00,
                'adelanto' => 750.00,
                'observaciones' => 'Incluir efectos de transición y corrección de color.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'video')->first()->idServicio,
            ],
            [
                'titulo' => 'Retoque de Fotos Antiguas',
                'descripcion' => 'Restauración y retoque de 10 fotos antiguas familiares.',
                'fecha_inicio' => now()->subDays(7),
                'fecha_entrega' => now()->addDays(3),
                'estado' => 'completado',
                'prioridad' => 'baja',
                'precio_total' => 400.00,
                'adelanto' => 400.00,
                'observaciones' => 'Fotos restauradas y entregadas al cliente.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'edicion')->first()->idServicio,
            ],
            [
                'titulo' => 'Fotos de Producto para E-commerce',
                'descripcion' => 'Fotografía profesional de 25 productos para tienda online.',
                'fecha_inicio' => now()->addDays(2),
                'fecha_entrega' => now()->addDays(8),
                'estado' => 'pendiente',
                'prioridad' => 'media',
                'precio_total' => 500.00,
                'adelanto' => 250.00,
                'observaciones' => 'Fondo blanco, múltiples ángulos por producto.',
                'idCliente' => $clientes->random()->idCliente,
                'idServicio' => $servicios->where('categoria', 'fotografia')->first()->idServicio,
            ],
        ];

        foreach ($trabajos as $trabajoData) {
            Trabajo::create([
                ...$trabajoData,
                'idUsuario' => $user->id,
            ]);
        }
    }
}
