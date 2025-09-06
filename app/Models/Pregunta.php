<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';
    public $timestamps = false;

    protected $fillable = [
        'id_encabezado',
        'pregunta',
    ];

    /**
     * Relación con la tabla de encabezados.
     */
    public function encabezado()
    {
        return $this->belongsTo(Encabezado::class, 'id_encabezado');
    }

    /**
     * Relación con la tabla de opciones.
     */
    public function opciones()
    {
        return $this->hasMany(Opcion::class, 'id_pregunta');
    }

    
}
