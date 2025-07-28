<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_trabajos', function (Blueprint $table) {
            $table->bigIncrements('idAsignacion');
            $table->bigInteger('idTrabajo')->unsigned();
            $table->bigInteger('idUsuarioEncargado')->unsigned();
            $table->string('turno', 50)->nullable();
            $table->date('fechaAsignacion');
            $table->timestamps();
            
            $table->foreign('idTrabajo')->references('idTrabajo')->on('trabajos')->onDelete('cascade');
            $table->foreign('idUsuarioEncargado')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_trabajos');
    }
};
