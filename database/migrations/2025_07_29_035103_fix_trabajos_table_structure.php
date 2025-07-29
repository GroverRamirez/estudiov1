<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            // Make old fields nullable or add defaults
            $table->date('fechaRegistro')->nullable()->change();
            $table->date('fechaEntrega')->nullable()->change();
            $table->unsignedBigInteger('idEstado')->nullable()->change();
            
            // Add default values for old fields
            DB::statement('UPDATE trabajos SET fechaRegistro = NOW() WHERE fechaRegistro IS NULL');
            DB::statement('UPDATE trabajos SET fechaEntrega = DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE fechaEntrega IS NULL');
            DB::statement('UPDATE trabajos SET idEstado = 1 WHERE idEstado IS NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            // Revert changes
            $table->date('fechaRegistro')->nullable(false)->change();
            $table->date('fechaEntrega')->nullable(false)->change();
            $table->unsignedBigInteger('idEstado')->nullable(false)->change();
        });
    }
};
