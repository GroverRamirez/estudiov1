<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pagos', function (Blueprint $table) {
            $table->bigIncrements('idDetallePago');
            $table->bigInteger('idPago')->unsigned();
            $table->decimal('monto', 10, 2);
            $table->date('fecha')->nullable();
            $table->string('comentario', 255)->nullable();
            $table->timestamps();
            
            $table->foreign('idPago')->references('idPago')->on('pagos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pagos');
    }
};
