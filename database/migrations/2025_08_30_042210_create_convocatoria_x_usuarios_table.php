<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('convocatoria_x_usuarios', function (Blueprint $table) {
            $table->foreignId('id_convocatoria')->constrained('convocatorias')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->boolean('estado');
            $table->primary(['id_convocatoria', 'id_usuario']); // Clave primaria compuesta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convocatoria_x_usuarios');
    }
};