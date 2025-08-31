<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('respuesta_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pregunta')->constrained('preguntas')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->string('respuesta_usuario');
            $table->boolean('correcta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuesta_usuarios');
    }
};