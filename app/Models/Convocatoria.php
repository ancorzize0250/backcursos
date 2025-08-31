<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    use HasFactory;

    protected $table = 'convocatorias';
    public $timestamps = false;
    
    protected $fillable = [
        'codigo',
        'nombre',
    ];

    /**
     * Relación con la tabla de modulos.
     */
    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'id_convocatoria');
    }
}
