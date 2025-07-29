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
        Schema::table('servicios', function (Blueprint $table) {
            // Rename existing column
            $table->renameColumn('nombreServicio', 'nombre');
            
            // Add new fields
            $table->text('descripcion')->after('nombre');
            $table->string('categoria', 50)->default('fotografia')->after('precio');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('categoria');
            $table->integer('duracion_estimada')->nullable()->after('estado');
            $table->string('imagen', 255)->nullable()->after('duracion_estimada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['descripcion', 'categoria', 'estado', 'duracion_estimada', 'imagen']);
            
            // Rename column back
            $table->renameColumn('nombre', 'nombreServicio');
        });
    }
};
