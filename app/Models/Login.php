<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;

    protected $table = 'logins';
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'correo',
        'password',
    ];

    /**
     * Relación con la tabla de usuarios.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
