<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@estudio.com',
                'password' => bcrypt('password'),
            ]);
        }

        $clientes = [
            [
                'nombre' => 'Juan Carlos Pérez',
                'email' => 'juan.perez@email.com',
                'telefono' => '+52 55 1234 5678',
                'direccion' => 'Av. Insurgentes Sur 123, Col. Del Valle, CDMX',
                'estado' => 'activo',
                'observaciones' => 'Cliente frecuente, prefiere trabajos de diseño gráfico',
                'fecha_registro' => now()->subDays(30),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'María González',
                'email' => 'maria.gonzalez@email.com',
                'telefono' => '+52 55 9876 5432',
                'direccion' => 'Calle Reforma 456, Col. Centro, CDMX',
                'estado' => 'activo',
                'observaciones' => 'Interesada en servicios de fotografía profesional',
                'fecha_registro' => now()->subDays(25),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@email.com',
                'telefono' => '+52 55 5555 1234',
                'direccion' => 'Blvd. Miguel Hidalgo 789, Col. Polanco, CDMX',
                'estado' => 'activo',
                'observaciones' => 'Empresario, necesita servicios corporativos',
                'fecha_registro' => now()->subDays(20),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'Ana Martínez',
                'email' => 'ana.martinez@email.com',
                'telefono' => '+52 55 4444 5678',
                'direccion' => 'Calle Roma 321, Col. Roma Norte, CDMX',
                'estado' => 'inactivo',
                'observaciones' => 'Cliente inactivo desde hace 2 meses',
                'fecha_registro' => now()->subDays(60),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'Luis Fernández',
                'email' => 'luis.fernandez@email.com',
                'telefono' => '+52 55 3333 9999',
                'direccion' => 'Av. Chapultepec 654, Col. Condesa, CDMX',
                'estado' => 'activo',
                'observaciones' => 'Fotógrafo independiente, colabora frecuentemente',
                'fecha_registro' => now()->subDays(15),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'Sofía López',
                'email' => 'sofia.lopez@email.com',
                'telefono' => '+52 55 2222 8888',
                'direccion' => 'Calle Coyoacán 147, Col. Coyoacán, CDMX',
                'estado' => 'activo',
                'observaciones' => 'Artista plástica, proyectos creativos',
                'fecha_registro' => now()->subDays(10),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'Roberto Silva',
                'email' => 'roberto.silva@email.com',
                'telefono' => '+52 55 1111 7777',
                'direccion' => 'Av. Universidad 258, Col. Coyoacán, CDMX',
                'estado' => 'activo',
                'observaciones' => 'Estudiante de diseño, proyectos académicos',
                'fecha_registro' => now()->subDays(5),
                'idUsuario' => $user->id,
            ],
            [
                'nombre' => 'Carmen Ruiz',
                'email' => 'carmen.ruiz@email.com',
                'telefono' => '+52 55 0000 6666',
                'direccion' => 'Calle San Ángel 963, Col. San Ángel, CDMX',
                'estado' => 'inactivo',
                'observaciones' => 'Cliente ocasional, último trabajo hace 3 meses',
                'fecha_registro' => now()->subDays(90),
                'idUsuario' => $user->id,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
