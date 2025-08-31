<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'identificacion',
        'nombres',
        'apellidos',
    ];

    /**
     * Relación con la tabla de login.
     */
    public function login()
    {
        return $this->hasOne(Login::class, 'id_usuario');
    }
}