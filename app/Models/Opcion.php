<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;

    protected $table = 'opciones';

    // Desactivamos los timestamps
    public $timestamps = false;

    protected $fillable = [
        'id_pregunta',
        'opcion',
        'descripcion_opcion',
        'correcta',
    ];

    /**
     * Relación con la tabla de preguntas.
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta');
    }
}
