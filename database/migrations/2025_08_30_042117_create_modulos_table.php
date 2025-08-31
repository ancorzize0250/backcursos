<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_convocatoria')->constrained('convocatorias')->onDelete('cascade');
            $table->string('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};