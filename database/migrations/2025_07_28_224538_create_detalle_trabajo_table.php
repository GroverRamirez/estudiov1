<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_trabajo', function (Blueprint $table) {
            $table->bigIncrements('idDetalle');
            $table->bigInteger('idTrabajo')->unsigned();
            $table->string('descripcion', 255)->nullable();
            $table->string('tamano', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->integer('cantidad');
            $table->string('tipoDocumento', 100)->nullable();
            $table->string('tipoEvento', 100)->nullable();
            $table->string('otros', 255)->nullable();
            $table->timestamps();
            
            $table->foreign('idTrabajo')->references('idTrabajo')->on('trabajos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_trabajo');
    }
};
