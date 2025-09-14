<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaUsuario extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'respuesta_usuarios';
    public $timestamps = false;
    
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_pregunta',
        'id_usuario',
        'respuesta_usuario',
        'correcta',
    ];

    // Relaciones

    /**
     * Una respuesta pertenece a una pregunta
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta');
    }

    /**
     * Una respuesta pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function scopeUltimaPreguntaRespondida($query, int $idUsuario, int $idConvocatoria)
    {
        return $query->where('id_usuario', $idUsuario)
            ->whereHas('pregunta.encabezado.modulo', function ($q) use ($idConvocatoria) {
                $q->where('id_convocatoria', $idConvocatoria);
            })
            ->orderByDesc('id_pregunta')
            ->limit(1);
    }
}