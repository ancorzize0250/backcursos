<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_modulo')->constrained('modulos')->onDelete('cascade');
            $table->string('pregunta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};