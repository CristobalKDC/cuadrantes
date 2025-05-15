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
        Schema::table('horario_entradas', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horario_entradas', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->time('hora_inicio')->nullable(false)->change();
            $table->time('hora_fin')->nullable(false)->change();
        });
    }
};
