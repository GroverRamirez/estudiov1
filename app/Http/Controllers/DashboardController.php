<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Trabajo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Estadísticas principales
        $stats = [
            'totalClientes' => Cliente::count(),
            'totalServicios' => Servicio::count(),
            'totalTrabajos' => Trabajo::count(),
            'totalPagos' => Pago::count(),
            'trabajosPendientes' => Trabajo::where('estado', 'pendiente')->count(),
            'pagosPendientes' => Pago::where('estado', 'pendiente')->count(),
            'pagosCompletados' => Pago::where('estado', 'completado')->count(),
            'totalIngresos' => Pago::where('estado', 'completado')->sum('monto'),
            'ingresosMes' => Pago::where('estado', 'completado')
                ->whereMonth('fecha_pago', now()->month)
                ->whereYear('fecha_pago', now()->year)
                ->sum('monto'),
            'pagosHoy' => Pago::where('estado', 'completado')
                ->whereDate('fecha_pago', today())
                ->sum('monto'),
        ];

        // Trabajos recientes
        $recentTrabajos = Trabajo::with(['cliente', 'servicio'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($trabajo) {
                return [
                    'id' => $trabajo->idTrabajo,
                    'titulo' => $trabajo->titulo,
                    'cliente' => $trabajo->cliente->nombre ?? 'N/A',
                    'servicio' => $trabajo->servicio->nombre ?? 'N/A',
                    'estado' => $trabajo->estado,
                    'precio_total' => $trabajo->precio_total,
                    'saldo_pendiente' => $trabajo->saldo_pendiente,
                    'fecha_inicio' => Carbon::parse($trabajo->fecha_inicio)->format('d/m/Y'),
                    'fecha_entrega' => Carbon::parse($trabajo->fecha_entrega)->format('d/m/Y'),
                ];
            });

        // Pagos recientes
        $recentPagos = Pago::with(['trabajo.cliente'])
            ->orderBy('fecha_pago', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($pago) {
                return [
                    'id' => $pago->idPago,
                    'monto' => $pago->monto,
                    'metodo_pago' => $pago->metodo_pago,
                    'estado' => $pago->estado,
                    'tipo_pago' => $pago->tipo_pago,
                    'fecha_pago' => Carbon::parse($pago->fecha_pago)->format('d/m/Y'),
                    'trabajo' => $pago->trabajo->titulo ?? 'N/A',
                    'cliente' => $pago->trabajo->cliente->nombre ?? 'N/A',
                ];
            });

        // Estadísticas por estado de trabajos
        $estadosTrabajos = [
            'pendiente' => [
                'count' => Trabajo::where('estado', 'pendiente')->count(),
                'color' => 'yellow',
                'icon' => 'clock'
            ],
            'en_proceso' => [
                'count' => Trabajo::where('estado', 'en proceso')->count(),
                'color' => 'blue',
                'icon' => 'play'
            ],
            'completado' => [
                'count' => Trabajo::where('estado', 'completado')->count(),
                'color' => 'green',
                'icon' => 'check'
            ],
            'cancelado' => [
                'count' => Trabajo::where('estado', 'cancelado')->count(),
                'color' => 'red',
                'icon' => 'x'
            ],
        ];

        // Estadísticas por estado de pagos
        $estadosPagos = [
            'completado' => [
                'count' => Pago::where('estado', 'completado')->count(),
                'total' => Pago::where('estado', 'completado')->sum('monto'),
                'color' => 'green',
                'icon' => 'check'
            ],
            'pendiente' => [
                'count' => Pago::where('estado', 'pendiente')->count(),
                'total' => Pago::where('estado', 'pendiente')->sum('monto'),
                'color' => 'yellow',
                'icon' => 'clock'
            ],
            'cancelado' => [
                'count' => Pago::where('estado', 'cancelado')->count(),
                'total' => Pago::where('estado', 'cancelado')->sum('monto'),
                'color' => 'red',
                'icon' => 'x'
            ],
            'reembolsado' => [
                'count' => Pago::where('estado', 'reembolsado')->count(),
                'total' => Pago::where('estado', 'reembolsado')->sum('monto'),
                'color' => 'blue',
                'icon' => 'refresh'
            ],
        ];

        // Métodos de pago más usados
        $metodosPago = Pago::selectRaw('metodo_pago, COUNT(*) as count, SUM(monto) as total')
            ->groupBy('metodo_pago')
            ->orderBy('count', 'desc')
            ->get();

        // Actividad del mes
        $actividadMes = [
            'trabajos_nuevos' => Trabajo::whereMonth('created_at', now()->month)->count(),
            'pagos_realizados' => Pago::whereMonth('fecha_pago', now()->month)->count(),
            'clientes_nuevos' => Cliente::whereMonth('created_at', now()->month)->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentTrabajos' => $recentTrabajos,
            'recentPagos' => $recentPagos,
            'estadosTrabajos' => $estadosTrabajos,
            'estadosPagos' => $estadosPagos,
            'metodosPago' => $metodosPago,
            'actividadMes' => $actividadMes
        ]);
    }
}
