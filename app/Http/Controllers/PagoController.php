<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\EstadoPago;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class PagoController extends Controller
{
    public function index(): Response
    {
        $pagos = Pago::with(['trabajo', 'cliente', 'estado'])
            ->orderBy('fechaPago', 'desc')
            ->paginate(10);

        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos
        ]);
    }

    public function create(): Response
    {
        $trabajos = Trabajo::with(['cliente', 'servicio'])->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $estados = EstadoPago::orderBy('nombre')->get();

        return Inertia::render('Pagos/Create', [
            'trabajos' => $trabajos,
            'clientes' => $clientes,
            'estados' => $estados
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'idTrabajo' => 'required|exists:trabajos,idTrabajo',
            'idCliente' => 'required|exists:clientes,idCliente',
            'montoTotal' => 'required|numeric|min:0',
            'aCuenta' => 'nullable|numeric|min:0',
            'fechaPago' => 'required|date',
            'idEstadoPago' => 'required|exists:estados_pago,id',
        ]);

        $saldoCalculado = $request->montoTotal - ($request->aCuenta ?? 0);

        $pago = Pago::create([
            'idTrabajo' => $request->idTrabajo,
            'idCliente' => $request->idCliente,
            'montoTotal' => $request->montoTotal,
            'aCuenta' => $request->aCuenta,
            'saldoCalculado' => $saldoCalculado,
            'fechaPago' => $request->fechaPago,
            'idEstadoPago' => $request->idEstadoPago,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago creado exitosamente',
            'pago' => $pago->load(['trabajo', 'cliente', 'estado'])
        ]);
    }

    public function show(Pago $pago): Response
    {
        $pago->load([
            'trabajo.cliente', 
            'trabajo.servicio', 
            'cliente', 
            'estado',
            'detalles'
        ]);

        return Inertia::render('Pagos/Show', [
            'pago' => $pago
        ]);
    }

    public function edit(Pago $pago): Response
    {
        $trabajos = Trabajo::with(['cliente', 'servicio'])->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $estados = EstadoPago::orderBy('nombre')->get();

        return Inertia::render('Pagos/Edit', [
            'pago' => $pago->load(['trabajo', 'cliente', 'estado']),
            'trabajos' => $trabajos,
            'clientes' => $clientes,
            'estados' => $estados
        ]);
    }

    public function update(Request $request, Pago $pago): JsonResponse
    {
        $request->validate([
            'idTrabajo' => 'required|exists:trabajos,idTrabajo',
            'idCliente' => 'required|exists:clientes,idCliente',
            'montoTotal' => 'required|numeric|min:0',
            'aCuenta' => 'nullable|numeric|min:0',
            'fechaPago' => 'required|date',
            'idEstadoPago' => 'required|exists:estados_pago,id',
        ]);

        $saldoCalculado = $request->montoTotal - ($request->aCuenta ?? 0);

        $pago->update([
            'idTrabajo' => $request->idTrabajo,
            'idCliente' => $request->idCliente,
            'montoTotal' => $request->montoTotal,
            'aCuenta' => $request->aCuenta,
            'saldoCalculado' => $saldoCalculado,
            'fechaPago' => $request->fechaPago,
            'idEstadoPago' => $request->idEstadoPago,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago actualizado exitosamente',
            'pago' => $pago->load(['trabajo', 'cliente', 'estado'])
        ]);
    }

    public function destroy(Pago $pago): JsonResponse
    {
        $pago->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pago eliminado exitosamente'
        ]);
    }
}
