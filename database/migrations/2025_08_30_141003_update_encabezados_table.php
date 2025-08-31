<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('encabezados', function (Blueprint $table) {
            // Agregamos la nueva columna y la clave foránea.
            // Las líneas para eliminar la columna id_pregunta se eliminaron,
            // ya que esta columna no existía en la tabla.
            $table->foreignId('id_modulo')->after('id')->constrained('modulos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('encabezados', function (Blueprint $table) {
            $table->dropForeign(['id_modulo']);
            $table->dropColumn('id_modulo');
        });
    }
};