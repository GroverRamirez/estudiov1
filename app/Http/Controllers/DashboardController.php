<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Trabajo;
use App\Models\Pago;
use App\Models\EstadoTrabajo;
use App\Models\EstadoPago;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'totalClientes' => Cliente::count(),
            'totalServicios' => Servicio::count(),
            'totalTrabajos' => Trabajo::count(),
            'totalPagos' => Pago::count(),
            'trabajosPendientes' => Trabajo::where('idEstado', 1)->count(), // Asumiendo que 1 es "Pendiente"
            'pagosPendientes' => Pago::where('idEstadoPago', 1)->count(), // Asumiendo que 1 es "Pendiente"
        ];

        $recentTrabajos = Trabajo::with(['cliente', 'servicio', 'estado'])
            ->orderBy('fechaRegistro', 'desc')
            ->limit(5)
            ->get();

        $recentPagos = Pago::with(['cliente', 'trabajo'])
            ->orderBy('fechaPago', 'desc')
            ->limit(5)
            ->get();

        $estadosTrabajo = EstadoTrabajo::withCount('trabajos')->get();
        $estadosPago = EstadoPago::withCount('pagos')->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentTrabajos' => $recentTrabajos,
            'recentPagos' => $recentPagos,
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago
        ]);
    }
}
