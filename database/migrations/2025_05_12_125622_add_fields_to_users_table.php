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
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellidos')->after('name');
            $table->string('dni')->unique()->after('apellidos');
            $table->string('telefono')->after('dni');
            $table->boolean('es_jefe')->default(false)->after('telefono');
            $table->string('apodo')->nullable()->after('es_jefe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['apellidos', 'dni', 'telefono', 'es_jefe', 'apodo']);
        });
    }
};
