<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ClienteController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Cliente::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        // Filter by estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->get('estado'));
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $clientes = $query->paginate(10)->withQueryString();

        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes,
            'filters' => $request->only(['search', 'estado', 'sort_by', 'sort_direction'])
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Clientes/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:clientes,email,idCliente',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
            'fecha_registro' => now(),
            'idUsuario' => auth()->id(),
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function show(Cliente $cliente): Response
    {
        $cliente->load(['usuario', 'trabajos.servicio', 'trabajos.estado', 'pagos.estado']);

        // Calculate statistics
        $estadisticas = [
            'total_trabajos' => $cliente->trabajos->count(),
            'trabajos_completados' => $cliente->trabajos->where('estado.nombre', 'Completado')->count(),
            'trabajos_pendientes' => $cliente->trabajos->whereIn('estado.nombre', ['Pendiente', 'En Proceso'])->count(),
            'total_pagado' => $cliente->pagos->where('estado.nombre', 'Completado')->sum('monto'),
            'saldo_pendiente' => $cliente->trabajos->sum('precio_total') - $cliente->pagos->where('estado.nombre', 'Completado')->sum('monto'),
        ];

        return Inertia::render('Clientes/Show', [
            'cliente' => $cliente,
            'estadisticas' => $estadisticas
        ]);
    }

    public function edit(Cliente $cliente): Response
    {
        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente
        ]);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:clientes,email,' . $cliente->idCliente . ',idCliente',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}
