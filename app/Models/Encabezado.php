<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encabezado extends Model
{
    use HasFactory;

    protected $table = 'encabezados';

    // Desactivamos los timestamps
    public $timestamps = false;

    protected $fillable = [
        'id_modulo',
        'texto',
    ];

    /**
     * Relación con la tabla de módulos.
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo');
    }


    public function preguntas() { return $this->hasMany(Pregunta::class, 'id_encabezado'); }
}