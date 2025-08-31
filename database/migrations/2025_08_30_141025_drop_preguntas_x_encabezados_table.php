<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('preguntas_x_encabezados');
    }

    public function down(): void
    {
        // La tabla no existe, no es necesario revertir
    }
};