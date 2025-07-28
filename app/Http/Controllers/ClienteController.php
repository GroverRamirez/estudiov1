<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ClienteController extends Controller
{
    public function index(): Response
    {
        $clientes = Cliente::with('usuario')
            ->orderBy('nombre')
            ->paginate(10);

        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Clientes/Create');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
        ]);

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'idUsuario' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente',
            'cliente' => $cliente->load('usuario')
        ]);
    }

    public function show(Cliente $cliente): Response
    {
        $cliente->load(['usuario', 'trabajos.servicio', 'trabajos.estado', 'pagos.estado']);

        return Inertia::render('Clientes/Show', [
            'cliente' => $cliente
        ]);
    }

    public function edit(Cliente $cliente): Response
    {
        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente
        ]);
    }

    public function update(Request $request, Cliente $cliente): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cliente actualizado exitosamente',
            'cliente' => $cliente->load('usuario')
        ]);
    }

    public function destroy(Cliente $cliente): JsonResponse
    {
        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado exitosamente'
        ]);
    }
}
