<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['idEstado']);
            
            // Add new fields
            $table->string('titulo', 200)->after('idTrabajo');
            $table->text('descripcion')->after('titulo');
            $table->date('fecha_inicio')->after('descripcion');
            $table->date('fecha_entrega')->after('fecha_inicio');
            $table->string('estado', 50)->default('pendiente')->after('fecha_entrega');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media')->after('estado');
            $table->decimal('precio_total', 10, 2)->default(0)->after('prioridad');
            $table->decimal('adelanto', 10, 2)->default(0)->after('precio_total');
            $table->text('observaciones')->nullable()->after('adelanto');
            
            // Drop old fields
            $table->dropColumn(['fechaRegistro', 'fechaEntrega', 'idEstado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['titulo', 'descripcion', 'fecha_inicio', 'fecha_entrega', 'estado', 'prioridad', 'precio_total', 'adelanto', 'observaciones']);
            
            // Add back old fields
            $table->date('fechaRegistro')->after('idServicio');
            $table->date('fechaEntrega')->after('fechaRegistro');
            $table->unsignedBigInteger('idEstado')->after('fechaEntrega');
        });
    }
};
