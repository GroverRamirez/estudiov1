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
        Schema::table('pagos', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['idCliente']);
            $table->dropForeign(['idEstadoPago']);
            
            // Add new fields
            $table->decimal('monto', 10, 2)->after('idPago');
            $table->date('fecha_pago')->after('monto');
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'deposito', 'otro'])->after('fecha_pago');
            $table->string('referencia', 255)->nullable()->after('metodo_pago');
            $table->enum('estado', ['completado', 'pendiente', 'cancelado', 'reembolsado'])->default('completado')->after('referencia');
            $table->enum('tipo_pago', ['adelanto', 'pago_parcial', 'pago_completo', 'reembolso'])->after('estado');
            $table->text('observaciones')->nullable()->after('tipo_pago');
            $table->unsignedBigInteger('idUsuario')->after('idTrabajo');
            
            // Drop old fields
            $table->dropColumn([
                'idCliente',
                'montoTotal',
                'aCuenta',
                'saldoCalculado',
                'fechaPago',
                'idEstadoPago'
            ]);
            
            // Add foreign key for usuario
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['idUsuario']);
            
            // Drop new fields
            $table->dropColumn([
                'monto',
                'fecha_pago',
                'metodo_pago',
                'referencia',
                'estado',
                'tipo_pago',
                'observaciones',
                'idUsuario'
            ]);
            
            // Add back old fields
            $table->unsignedBigInteger('idCliente');
            $table->decimal('montoTotal', 10, 2);
            $table->decimal('aCuenta', 10, 2)->nullable();
            $table->decimal('saldoCalculado', 10, 2);
            $table->date('fechaPago');
            $table->unsignedBigInteger('idEstadoPago');
            
            // Add back foreign keys
            $table->foreign('idCliente')->references('idCliente')->on('clientes')->onDelete('cascade');
            $table->foreign('idEstadoPago')->references('id')->on('estados_pago')->onDelete('cascade');
        });
    }
};
