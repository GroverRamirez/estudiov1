<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('idPago');
            $table->bigInteger('idTrabajo')->unsigned();
            $table->bigInteger('idCliente')->unsigned();
            $table->decimal('montoTotal', 10, 2);
            $table->decimal('aCuenta', 10, 2)->nullable();
            $table->decimal('saldoCalculado', 10, 2);
            $table->date('fechaPago');
            $table->smallInteger('idEstadoPago')->unsigned();
            $table->timestamps();
            
            $table->foreign('idTrabajo')->references('idTrabajo')->on('trabajos')->onDelete('cascade');
            $table->foreign('idCliente')->references('idCliente')->on('clientes')->onDelete('cascade');
            $table->foreign('idEstadoPago')->references('id')->on('estados_pago')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
