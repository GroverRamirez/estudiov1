<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServicioController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Servicio::with('usuario');

        // Search
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filter by status
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $servicios = $query->paginate(10)->withQueryString();

        return Inertia::render('Servicios/Index', [
            'servicios' => $servicios,
            'filters' => $request->only(['search', 'categoria', 'estado', 'sort_by', 'sort_direction'])
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Servicios/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:50',
            'estado' => 'required|in:activo,inactivo',
            'duracion_estimada' => 'nullable|integer|min:0',
            'imagen' => 'nullable|url|max:255',
        ]);

        $servicio = Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
            'estado' => $request->estado,
            'duracion_estimada' => $request->duracion_estimada,
            'imagen' => $request->imagen,
            'idUsuario' => auth()->id(),
        ]);

        return redirect()->route('servicios.index');
    }

    public function show(Servicio $servicio): Response
    {
        $servicio->load(['usuario', 'trabajos.cliente', 'trabajos.estado']);

        return Inertia::render('Servicios/Show', [
            'servicio' => $servicio
        ]);
    }

    public function edit(Servicio $servicio): Response
    {
        return Inertia::render('Servicios/Edit', [
            'servicio' => $servicio
        ]);
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:50',
            'estado' => 'required|in:activo,inactivo',
            'duracion_estimada' => 'nullable|integer|min:0',
            'imagen' => 'nullable|url|max:255',
        ]);

        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
            'estado' => $request->estado,
            'duracion_estimada' => $request->duracion_estimada,
            'imagen' => $request->imagen,
        ]);

        return redirect()->route('servicios.index');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('servicios.index');
    }
}
