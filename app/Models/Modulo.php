<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';
    public $timestamps = false;
    
    protected $fillable = [
        'id_convocatoria',
        'nombre',
    ];

    /**
     * Relación con la tabla de convocatorias.
     */
    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class, 'id_convocatoria');
    }

    /**
     * Relación con la tabla de preguntas.
     */
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'id_modulo');
    }
}