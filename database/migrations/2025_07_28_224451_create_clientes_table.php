<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('idCliente');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->bigInteger('idUsuario')->unsigned();
            $table->timestamps();
            
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
