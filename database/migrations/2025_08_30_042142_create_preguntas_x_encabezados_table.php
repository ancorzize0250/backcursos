<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preguntas_x_encabezados', function (Blueprint $table) {
            $table->foreignId('id_pregunta')->constrained('preguntas')->onDelete('cascade');
            $table->foreignId('id_encabezado')->constrained('encabezados')->onDelete('cascade');
            $table->primary(['id_pregunta', 'id_encabezado']); // Clave primaria compuesta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas_x_encabezados');
    }
};