<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Trabajo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Pago::with(['trabajo.cliente', 'trabajo.servicio', 'usuario']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('referencia', 'like', '%' . $request->search . '%')
                  ->orWhere('observaciones', 'like', '%' . $request->search . '%')
                  ->orWhereHas('trabajo', function ($tq) use ($request) {
                      $tq->where('titulo', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('trabajo.cliente', function ($cq) use ($request) {
                      $cq->where('nombre', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->tipo_pago);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_pago', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_pago', '<=', $request->fecha_hasta);
        }

        if ($request->filled('trabajo_id')) {
            $query->where('idTrabajo', $request->trabajo_id);
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'fecha_pago');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $pagos = $query->paginate(10)->withQueryString();

        // Estadísticas financieras
        $estadisticas = [
            'total_pagado' => Pago::where('estado', 'completado')->sum('monto'),
            'total_pendiente' => Pago::where('estado', 'pendiente')->sum('monto'),
            'total_cancelado' => Pago::where('estado', 'cancelado')->sum('monto'),
            'total_reembolsado' => Pago::where('estado', 'reembolsado')->sum('monto'),
            'pagos_hoy' => Pago::where('estado', 'completado')
                ->whereDate('fecha_pago', today())
                ->sum('monto'),
            'pagos_mes' => Pago::where('estado', 'completado')
                ->whereMonth('fecha_pago', now()->month)
                ->whereYear('fecha_pago', now()->year)
                ->sum('monto'),
        ];

        // Trabajos para filtros
        $trabajos = Trabajo::with(['cliente', 'servicio'])
            ->where('saldo_pendiente', '>', 0)
            ->orderBy('titulo')
            ->get();

        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos,
            'trabajos' => $trabajos,
            'estadisticas' => $estadisticas,
            'filters' => $request->only([
                'search', 'estado', 'metodo_pago', 'tipo_pago', 
                'fecha_desde', 'fecha_hasta', 'trabajo_id', 
                'sort_by', 'sort_direction'
            ])
        ]);
    }

    public function create(): Response
    {
        $trabajos = Trabajo::with(['cliente', 'servicio'])
            ->where('saldo_pendiente', '>', 0)
            ->orderBy('titulo')
            ->get();

        return Inertia::render('Pagos/Create', [
            'trabajos' => $trabajos
        ]);
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'idTrabajo' => 'required|exists:trabajos,idTrabajo',
            'monto' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,deposito,otro',
            'referencia' => 'nullable|string|max:255',
            'estado' => 'required|in:completado,pendiente,cancelado,reembolsado',
            'tipo_pago' => 'required|in:adelanto,pago_parcial,pago_completo,reembolso',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Verificar que el trabajo existe y tiene saldo pendiente
        $trabajo = Trabajo::findOrFail($request->idTrabajo);
        
        if ($trabajo->saldo_pendiente <= 0 && $request->tipo_pago !== 'reembolso') {
            return back()->withErrors(['idTrabajo' => 'Este trabajo ya está completamente pagado.']);
        }

        // Crear el pago
        $pago = Pago::create([
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'metodo_pago' => $request->metodo_pago,
            'referencia' => $request->referencia,
            'estado' => $request->estado,
            'tipo_pago' => $request->tipo_pago,
            'observaciones' => $request->observaciones,
            'idTrabajo' => $request->idTrabajo,
            'idUsuario' => auth()->id(),
        ]);

        // Actualizar saldo del trabajo si el pago está completado
        if ($request->estado === 'completado') {
            $this->actualizarSaldoTrabajo($trabajo, $request->monto, $request->tipo_pago);
        }

        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function show(Pago $pago): Response
    {
        $pago->load([
            'trabajo.cliente', 
            'trabajo.servicio', 
            'usuario'
        ]);

        return Inertia::render('Pagos/Show', [
            'pago' => $pago
        ]);
    }

    public function edit(Pago $pago): Response
    {
        $trabajos = Trabajo::with(['cliente', 'servicio'])
            ->where('saldo_pendiente', '>', 0)
            ->orWhere('idTrabajo', $pago->idTrabajo)
            ->orderBy('titulo')
            ->get();

        return Inertia::render('Pagos/Edit', [
            'pago' => $pago->load(['trabajo.cliente', 'trabajo.servicio']),
            'trabajos' => $trabajos
        ]);
    }

    public function update(Request $request, Pago $pago): Response
    {
        $request->validate([
            'idTrabajo' => 'required|exists:trabajos,idTrabajo',
            'monto' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,deposito,otro',
            'referencia' => 'nullable|string|max:255',
            'estado' => 'required|in:completado,pendiente,cancelado,reembolsado',
            'tipo_pago' => 'required|in:adelanto,pago_parcial,pago_completo,reembolso',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Revertir el saldo anterior si el pago estaba completado
        if ($pago->estado === 'completado' && $request->estado !== 'completado') {
            $this->revertirSaldoTrabajo($pago);
        }

        // Actualizar el pago
        $pago->update([
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'metodo_pago' => $request->metodo_pago,
            'referencia' => $request->referencia,
            'estado' => $request->estado,
            'tipo_pago' => $request->tipo_pago,
            'observaciones' => $request->observaciones,
            'idTrabajo' => $request->idTrabajo,
        ]);

        // Actualizar saldo del trabajo si el pago está completado
        if ($request->estado === 'completado') {
            $trabajo = Trabajo::findOrFail($request->idTrabajo);
            $this->actualizarSaldoTrabajo($trabajo, $request->monto, $request->tipo_pago);
        }

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(Pago $pago): Response
    {
        // Revertir el saldo si el pago estaba completado
        if ($pago->estado === 'completado') {
            $this->revertirSaldoTrabajo($pago);
        }

        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    /**
     * Actualizar el saldo pendiente del trabajo
     */
    private function actualizarSaldoTrabajo(Trabajo $trabajo, float $monto, string $tipoPago): void
    {
        if ($tipoPago === 'reembolso') {
            $trabajo->increment('saldo_pendiente', $monto);
        } else {
            $trabajo->decrement('saldo_pendiente', $monto);
        }
        
        // Asegurar que el saldo no sea negativo
        if ($trabajo->saldo_pendiente < 0) {
            $trabajo->update(['saldo_pendiente' => 0]);
        }
    }

    /**
     * Revertir el saldo del trabajo al eliminar/actualizar un pago
     */
    private function revertirSaldoTrabajo(Pago $pago): void
    {
        $trabajo = $pago->trabajo;
        
        if ($pago->tipo_pago === 'reembolso') {
            $trabajo->decrement('saldo_pendiente', $pago->monto);
        } else {
            $trabajo->increment('saldo_pendiente', $pago->monto);
        }
    }
}
