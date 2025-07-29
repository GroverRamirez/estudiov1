<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Trabajo;
use App\Models\User;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener trabajos y usuarios existentes
        $trabajos = Trabajo::all();
        $usuarios = User::all();
        
        if ($trabajos->isEmpty() || $usuarios->isEmpty()) {
            $this->command->warn('No hay trabajos o usuarios disponibles para crear pagos.');
            return;
        }

        $metodosPago = ['efectivo', 'tarjeta', 'transferencia', 'deposito', 'otro'];
        $estados = ['completado', 'pendiente', 'cancelado', 'reembolsado'];
        $tiposPago = ['adelanto', 'pago_parcial', 'pago_completo', 'reembolso'];

        // Crear pagos para cada trabajo
        foreach ($trabajos as $trabajo) {
            $usuario = $usuarios->random();
            
            // Determinar cuántos pagos hacer para este trabajo
            $numPagos = rand(1, 3);
            $saldoRestante = $trabajo->saldo_pendiente;
            
            for ($i = 0; $i < $numPagos && $saldoRestante > 0; $i++) {
                // Calcular monto del pago
                if ($i === $numPagos - 1 || $saldoRestante <= 100) {
                    // Último pago o saldo pequeño - pago completo
                    $monto = $saldoRestante;
                    $tipoPago = 'pago_completo';
                } else {
                    // Pago parcial
                    $monto = rand(50, min($saldoRestante - 50, 200));
                    $tipoPago = $i === 0 ? 'adelanto' : 'pago_parcial';
                }
                
                // Determinar estado del pago
                $estado = $estados[array_rand($estados)];
                if ($estado === 'cancelado' || $estado === 'reembolsado') {
                    $monto = rand(20, 100); // Montos menores para pagos cancelados/reembolsados
                }
                
                // Fecha del pago (dentro del rango del trabajo)
                $fechaInicio = \Carbon\Carbon::parse($trabajo->fecha_inicio);
                $fechaEntrega = \Carbon\Carbon::parse($trabajo->fecha_entrega);
                $fechaPago = $fechaInicio->addDays(rand(0, $fechaInicio->diffInDays($fechaEntrega)));
                
                // Crear el pago
                Pago::create([
                    'monto' => $monto,
                    'fecha_pago' => $fechaPago,
                    'metodo_pago' => $metodosPago[array_rand($metodosPago)],
                    'referencia' => $estado === 'completado' ? 'REF-' . strtoupper(uniqid()) : null,
                    'estado' => $estado,
                    'tipo_pago' => $tipoPago,
                    'observaciones' => $this->getObservaciones($tipoPago, $estado),
                    'idTrabajo' => $trabajo->idTrabajo,
                    'idUsuario' => $usuario->id,
                ]);
                
                // Actualizar saldo restante solo si el pago está completado
                if ($estado === 'completado') {
                    $saldoRestante -= $monto;
                }
            }
        }

        $this->command->info('Pagos creados exitosamente.');
    }

    /**
     * Generar observaciones realistas para los pagos
     */
    private function getObservaciones(string $tipoPago, string $estado): ?string
    {
        $observaciones = [
            'adelanto' => [
                'Pago inicial del trabajo',
                'Adelanto para comenzar el proyecto',
                'Pago de reserva',
                'Anticipo del cliente'
            ],
            'pago_parcial' => [
                'Pago parcial del trabajo',
                'Segunda cuota del proyecto',
                'Pago intermedio',
                'Cuota del servicio'
            ],
            'pago_completo' => [
                'Pago completo del trabajo',
                'Liquidación final del proyecto',
                'Pago total del servicio',
                'Saldado completo'
            ],
            'reembolso' => [
                'Reembolso por cancelación',
                'Devolución de pago',
                'Reembolso por error',
                'Devolución parcial'
            ]
        ];

        $estadoObservaciones = [
            'completado' => 'Pago procesado exitosamente',
            'pendiente' => 'Pendiente de confirmación',
            'cancelado' => 'Pago cancelado por el cliente',
            'reembolsado' => 'Reembolso procesado'
        ];

        $tipoObs = $observaciones[$tipoPago][array_rand($observaciones[$tipoPago])];
        $estadoObs = $estadoObservaciones[$estado];
        
        return $tipoObs . '. ' . $estadoObs;
    }
}
