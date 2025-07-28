<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\PagoController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Clientes
    Route::resource('clientes', ClienteController::class);
    
    // Servicios
    Route::resource('servicios', ServicioController::class);
    
    // Trabajos
    Route::resource('trabajos', TrabajoController::class);
    
    // Pagos
    Route::resource('pagos', PagoController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
