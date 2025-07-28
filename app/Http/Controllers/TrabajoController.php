<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\EstadoTrabajo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class TrabajoController extends Controller
{
    public function index(): Response
    {
        $trabajos = Trabajo::with(['cliente', 'servicio', 'usuario', 'estado'])
            ->orderBy('fechaRegistro', 'desc')
            ->paginate(10);

        return Inertia::render('Trabajos/Index', [
            'trabajos' => $trabajos
        ]);
    }

    public function create(): Response
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = Servicio::orderBy('nombreServicio')->get();
        $estados = EstadoTrabajo::orderBy('nombre')->get();

        return Inertia::render('Trabajos/Create', [
            'clientes' => $clientes,
            'servicios' => $servicios,
            'estados' => $estados
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'idServicio' => 'required|exists:servicios,idServicio',
            'fechaRegistro' => 'required|date',
            'fechaEntrega' => 'required|date|after:fechaRegistro',
            'idEstado' => 'required|exists:estados_trabajo,id',
        ]);

        $trabajo = Trabajo::create([
            'idCliente' => $request->idCliente,
            'idServicio' => $request->idServicio,
            'idUsuario' => auth()->id(),
            'fechaRegistro' => $request->fechaRegistro,
            'fechaEntrega' => $request->fechaEntrega,
            'idEstado' => $request->idEstado,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trabajo creado exitosamente',
            'trabajo' => $trabajo->load(['cliente', 'servicio', 'usuario', 'estado'])
        ]);
    }

    public function show(Trabajo $trabajo): Response
    {
        $trabajo->load([
            'cliente', 
            'servicio', 
            'usuario', 
            'estado', 
            'detalle',
            'asignaciones.usuarioEncargado',
            'pagos.estado'
        ]);

        return Inertia::render('Trabajos/Show', [
            'trabajo' => $trabajo
        ]);
    }

    public function edit(Trabajo $trabajo): Response
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = Servicio::orderBy('nombreServicio')->get();
        $estados = EstadoTrabajo::orderBy('nombre')->get();

        return Inertia::render('Trabajos/Edit', [
            'trabajo' => $trabajo->load(['cliente', 'servicio', 'estado']),
            'clientes' => $clientes,
            'servicios' => $servicios,
            'estados' => $estados
        ]);
    }

    public function update(Request $request, Trabajo $trabajo): JsonResponse
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'idServicio' => 'required|exists:servicios,idServicio',
            'fechaRegistro' => 'required|date',
            'fechaEntrega' => 'required|date|after:fechaRegistro',
            'idEstado' => 'required|exists:estados_trabajo,id',
        ]);

        $trabajo->update([
            'idCliente' => $request->idCliente,
            'idServicio' => $request->idServicio,
            'fechaRegistro' => $request->fechaRegistro,
            'fechaEntrega' => $request->fechaEntrega,
            'idEstado' => $request->idEstado,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trabajo actualizado exitosamente',
            'trabajo' => $trabajo->load(['cliente', 'servicio', 'usuario', 'estado'])
        ]);
    }

    public function destroy(Trabajo $trabajo): JsonResponse
    {
        $trabajo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Trabajo eliminado exitosamente'
        ]);
    }
}
