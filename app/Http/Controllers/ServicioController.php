<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServicioController extends Controller
{
    public function index(): Response
    {
        $servicios = Servicio::with('usuario')
            ->orderBy('nombreServicio')
            ->paginate(10);

        return Inertia::render('Servicios/Index', [
            'servicios' => $servicios
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Servicios/Create');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombreServicio' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
        ]);

        $servicio = Servicio::create([
            'nombreServicio' => $request->nombreServicio,
            'precio' => $request->precio,
            'idUsuario' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Servicio creado exitosamente',
            'servicio' => $servicio->load('usuario')
        ]);
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

    public function update(Request $request, Servicio $servicio): JsonResponse
    {
        $request->validate([
            'nombreServicio' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
        ]);

        $servicio->update([
            'nombreServicio' => $request->nombreServicio,
            'precio' => $request->precio,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Servicio actualizado exitosamente',
            'servicio' => $servicio->load('usuario')
        ]);
    }

    public function destroy(Servicio $servicio): JsonResponse
    {
        $servicio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Servicio eliminado exitosamente'
        ]);
    }
}
