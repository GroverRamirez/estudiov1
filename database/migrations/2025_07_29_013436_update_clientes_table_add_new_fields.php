<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Remove old fields
            $table->dropColumn(['apellido']);
            
            // Add new fields
            $table->string('direccion', 255)->after('email');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('direccion');
            $table->text('observaciones')->nullable()->after('estado');
            $table->timestamp('fecha_registro')->nullable()->after('observaciones');
            
            // Make email and telefono required
            $table->string('email', 150)->nullable(false)->change();
            $table->string('telefono', 20)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['direccion', 'estado', 'observaciones', 'fecha_registro']);
            
            // Add back old fields
            $table->string('apellido', 100)->after('nombre');
            
            // Make email and telefono nullable again
            $table->string('email', 150)->nullable()->change();
            $table->string('telefono', 20)->nullable()->change();
        });
    }
};
