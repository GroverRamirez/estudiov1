<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->bigIncrements('idTrabajo');
            $table->bigInteger('idCliente')->unsigned();
            $table->bigInteger('idServicio')->unsigned();
            $table->bigInteger('idUsuario')->unsigned();
            $table->date('fechaRegistro');
            $table->date('fechaEntrega');
            $table->smallInteger('idEstado')->unsigned();
            $table->timestamps();
            
            $table->foreign('idCliente')->references('idCliente')->on('clientes')->onDelete('cascade');
            $table->foreign('idServicio')->references('idServicio')->on('servicios')->onDelete('cascade');
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idEstado')->references('id')->on('estados_trabajo')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};
