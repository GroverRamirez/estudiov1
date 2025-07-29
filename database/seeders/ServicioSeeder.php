<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\User;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have a user
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $servicios = [
            [
                'nombre' => 'Sesión de Fotos Profesional',
                'descripcion' => 'Sesión fotográfica profesional con edición incluida. Incluye 20 fotos editadas y 5 fotos retocadas profesionalmente.',
                'precio' => 150.00,
                'categoria' => 'fotografia',
                'estado' => 'activo',
                'duracion_estimada' => 120,
                'imagen' => 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?w=800',
            ],
            [
                'nombre' => 'Video Corporativo',
                'descripcion' => 'Producción de video corporativo con edición profesional. Incluye guión, grabación y post-producción.',
                'precio' => 500.00,
                'categoria' => 'video',
                'estado' => 'activo',
                'duracion_estimada' => 300,
                'imagen' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=800',
            ],
            [
                'nombre' => 'Edición de Fotos Avanzada',
                'descripcion' => 'Servicio de edición profesional de fotos. Retoque de piel, corrección de color y efectos especiales.',
                'precio' => 80.00,
                'categoria' => 'edicion',
                'estado' => 'activo',
                'duracion_estimada' => 60,
                'imagen' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800',
            ],
            [
                'nombre' => 'Impresión de Fotos',
                'descripcion' => 'Impresión de alta calidad en diferentes tamaños. Papel fotográfico profesional.',
                'precio' => 25.00,
                'categoria' => 'impresion',
                'estado' => 'activo',
                'duracion_estimada' => 30,
                'imagen' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=800',
            ],
            [
                'nombre' => 'Cobertura de Eventos',
                'descripcion' => 'Cobertura fotográfica completa de eventos sociales y corporativos. Incluye edición y álbum digital.',
                'precio' => 300.00,
                'categoria' => 'eventos',
                'estado' => 'activo',
                'duracion_estimada' => 240,
                'imagen' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800',
            ],
            [
                'nombre' => 'Fotos de Producto',
                'descripcion' => 'Fotografía profesional de productos para catálogos y redes sociales. Incluye edición y retoque.',
                'precio' => 100.00,
                'categoria' => 'fotografia',
                'estado' => 'activo',
                'duracion_estimada' => 90,
                'imagen' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800',
            ],
            [
                'nombre' => 'Video Musical',
                'descripcion' => 'Producción de video musical con edición creativa y efectos especiales.',
                'precio' => 800.00,
                'categoria' => 'video',
                'estado' => 'activo',
                'duracion_estimada' => 480,
                'imagen' => 'https://images.unsplash.com/photo-1571330735066-03aaa9429d93?w=800',
            ],
            [
                'nombre' => 'Retoque de Fotos Antiguas',
                'descripcion' => 'Restauración y retoque de fotos antiguas. Eliminación de manchas y restauración de colores.',
                'precio' => 120.00,
                'categoria' => 'edicion',
                'estado' => 'activo',
                'duracion_estimada' => 120,
                'imagen' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
            ],
        ];

        foreach ($servicios as $servicioData) {
            Servicio::create([
                ...$servicioData,
                'idUsuario' => $user->id,
            ]);
        }
    }
}
