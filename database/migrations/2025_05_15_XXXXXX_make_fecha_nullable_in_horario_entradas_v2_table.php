<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFechaNullableInHorarioEntradasV2Table extends Migration
{
    public function up()
    {
        Schema::table('horario_entradas', function (Blueprint $table) {
            $table->date('fecha')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('horario_entradas', function (Blueprint $table) {
            $table->date('fecha')->nullable(false)->change();
        });
    }
}
