<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvocatoriaUsuario  extends Model
{
    use HasFactory;

    protected $table = 'convocatoria_x_usuarios';
    public $timestamps = false;
    
    protected $fillable = [
        'id_convocatoria',
        'id_usuario',
        'estado'
    ];



    
}
