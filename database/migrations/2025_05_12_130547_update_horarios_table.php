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
        Schema::table('horarios', function (Blueprint $table) {
            // Verifica si la columna 'creado_por' ya existe antes de agregarla
            if (!Schema::hasColumn('horarios', 'creado_por')) {
                $table->foreignId('creado_por')->constrained('users')->onDelete('cascade');
            }

            // Agregar las demás columnas si no existen
            if (!Schema::hasColumn('horarios', 'titulo')) {
                $table->string('titulo')->nullable();
            }

            if (!Schema::hasColumn('horarios', 'fecha_inicio')) {
                $table->date('fecha_inicio');
            }

            if (!Schema::hasColumn('horarios', 'fecha_fin')) {
                $table->date('fecha_fin');
            }

            // Verifica si las columnas 'created_at' y 'updated_at' ya existen antes de agregarlas
            if (!Schema::hasColumn('horarios', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('horarios', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropColumn(['creado_por', 'titulo', 'fecha_inicio', 'fecha_fin', 'created_at', 'updated_at']);
        });
    }
};
