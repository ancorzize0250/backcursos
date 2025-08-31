<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('opciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pregunta')->constrained('preguntas')->onDelete('cascade');
            $table->string('opcion');
            $table->string('descripcion_opcion');
            $table->boolean('correcta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opciones');
    }
};