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
        Schema::table('trabajos', function (Blueprint $table) {
            // Add saldo_pendiente column if it doesn't exist
            if (!Schema::hasColumn('trabajos', 'saldo_pendiente')) {
                $table->decimal('saldo_pendiente', 10, 2)->default(0)->after('precio_total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            if (Schema::hasColumn('trabajos', 'saldo_pendiente')) {
                $table->dropColumn('saldo_pendiente');
            }
        });
    }
}; 