<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrabajoController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Trabajo::with(['cliente', 'servicio']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->search . '%')
                  ->orWhereHas('cliente', function ($cq) use ($request) {
                      $cq->where('nombre', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('servicio', function ($sq) use ($request) {
                      $sq->where('nombre', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('cliente_id')) {
            $query->where('idCliente', $request->cliente_id);
        }

        if ($request->filled('servicio_id')) {
            $query->where('idServicio', $request->servicio_id);
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $trabajos = $query->paginate(10)->withQueryString();

        // Get data for filters
        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = Servicio::orderBy('nombre')->get();
        $estados = [
            'pendiente' => 'Pendiente',
            'en proceso' => 'En Proceso',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado'
        ];

        return Inertia::render('Trabajos/Index', [
            'trabajos' => $trabajos,
            'filters' => $request->only(['search', 'estado', 'prioridad', 'cliente_id', 'servicio_id', 'sort_by', 'sort_direction']),
            'clientes' => $clientes,
            'servicios' => $servicios,
            'estados' => $estados
        ]);
    }

    public function create(): Response
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = Servicio::orderBy('nombre')->get();
        $estados = [
            'pendiente' => 'Pendiente',
            'en proceso' => 'En Proceso',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado'
        ];

        return Inertia::render('Trabajos/Create', [
            'clientes' => $clientes,
            'servicios' => $servicios,
            'estados' => $estados
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'required|date|after:fecha_inicio',
            'estado' => 'required|string|max:50',
            'prioridad' => 'required|in:baja,media,alta,urgente',
            'precio_total' => 'required|numeric|min:0',
            'adelanto' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
            'idCliente' => 'required|exists:clientes,idCliente',
            'idServicio' => 'required|exists:servicios,idServicio',
        ]);

        $trabajo = Trabajo::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_entrega' => $request->fecha_entrega,
            'estado' => $request->estado,
            'prioridad' => $request->prioridad,
            'precio_total' => $request->precio_total,
            'adelanto' => $request->adelanto ?? 0,
            'observaciones' => $request->observaciones,
            'idCliente' => $request->idCliente,
            'idServicio' => $request->idServicio,
            'idUsuario' => auth()->id(),
        ]);

        return redirect()->route('trabajos.index');
    }

    public function show(Trabajo $trabajo): Response
    {
        $trabajo->load([
            'cliente', 
            'servicio', 
            'usuario'
        ]);

        return Inertia::render('Trabajos/Show', [
            'trabajo' => $trabajo
        ]);
    }

    public function edit(Trabajo $trabajo): Response
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = Servicio::orderBy('nombre')->get();
        $estados = [
            'pendiente' => 'Pendiente',
            'en proceso' => 'En Proceso',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado'
        ];

        return Inertia::render('Trabajos/Edit', [
            'trabajo' => $trabajo->load(['cliente', 'servicio']),
            'clientes' => $clientes,
            'servicios' => $servicios,
            'estados' => $estados
        ]);
    }

    public function update(Request $request, Trabajo $trabajo)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'required|date|after:fecha_inicio',
            'estado' => 'required|string|max:50',
            'prioridad' => 'required|in:baja,media,alta,urgente',
            'precio_total' => 'required|numeric|min:0',
            'adelanto' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
            'idCliente' => 'required|exists:clientes,idCliente',
            'idServicio' => 'required|exists:servicios,idServicio',
        ]);

        $trabajo->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_entrega' => $request->fecha_entrega,
            'estado' => $request->estado,
            'prioridad' => $request->prioridad,
            'precio_total' => $request->precio_total,
            'adelanto' => $request->adelanto ?? 0,
            'observaciones' => $request->observaciones,
            'idCliente' => $request->idCliente,
            'idServicio' => $request->idServicio,
        ]);

        return redirect()->route('trabajos.index');
    }

    public function destroy(Trabajo $trabajo)
    {
        $trabajo->delete();

        return redirect()->route('trabajos.index');
    }
}
