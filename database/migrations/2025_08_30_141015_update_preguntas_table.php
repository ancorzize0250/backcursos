<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('preguntas', function (Blueprint $table) {
            // Eliminar la clave foránea si existe
            $table->dropForeign(['id_modulo']);
            // Eliminar la columna id_modulo si existe
            $table->dropColumn('id_modulo');
            // Agregar la nueva columna y la clave foránea
            $table->foreignId('id_encabezado')->after('id')->constrained('encabezados')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->dropForeign(['id_encabezado']);
            $table->dropColumn('id_encabezado');
            // Revertir los cambios si es necesario
        });
    }
};