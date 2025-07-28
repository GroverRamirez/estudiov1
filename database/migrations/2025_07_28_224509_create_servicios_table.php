<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('idServicio');
            $table->string('nombreServicio', 100);
            $table->decimal('precio', 10, 2);
            $table->bigInteger('idUsuario')->unsigned();
            $table->timestamps();
            
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
