<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('respuesta_usuarios', function (Blueprint $table) {
            $table->unique(['id_pregunta', 'id_usuario'], 'respuesta_unica');
        });
    }

    public function down(): void
    {
        Schema::table('respuesta_usuarios', function (Blueprint $table) {
            $table->dropUnique('respuesta_unica');
        });
    }
};

