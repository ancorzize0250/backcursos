<?php

namespace App\Repositories;

use App\Models\Pregunta;
use Illuminate\Support\Facades\DB;

class PreguntaRepository
{
    /**
     * Crea una nueva pregunta en la base de datos.
     *
     * @param array $data
     * @return \App\Models\Pregunta
     */
    public function create(array $data)
    {
        return Pregunta::create($data);
    }

    /**
     * Obtiene las preguntas con sus opciones y encabezado.
     *
     * @param int $id_modulo
     * @param int $id_convocatoria
     * @param int $id_usuario
     * @return \Illuminate\Support\Collection
     */
    public function getPreguntasByModuloAndUser(int $id_modulo, int $id_convocatoria, int $id_usuario)
    {
       return DB::table('convocatorias as c')
            ->join('modulos as m', 'c.id', '=', 'm.id_convocatoria')
            ->join('encabezados as e', 'm.id', '=', 'e.id_modulo')
            ->join('preguntas as p', 'p.id_encabezado', '=', 'e.id')
            ->join('opciones as op', 'op.id_pregunta', '=', 'p.id')
            ->join('convocatoria_x_usuarios as cxu', 'cxu.id_convocatoria', '=', 'c.id')
            ->join('usuarios as u', 'u.id', '=', 'cxu.id_usuario')
            ->leftJoin('respuesta_usuarios as ru', function ($join) use ($id_usuario) {
                $join->on('ru.id_pregunta', '=', 'p.id')
                     ->where('ru.id_usuario', '=', $id_usuario);
            })
            ->select(
                'c.id as id_convocatoria',
                'c.nombre as convocatoria_nombre',
                'm.id as id_modulo',
                'm.nombre as modulo_nombre',
                'e.id as id_encabezado',
                'e.texto as encabezado',
                'p.id as id_pregunta',
                'p.pregunta',
                'op.opcion',
                'op.descripcion_opcion',
                'op.correcta'
            )
            ->where('m.id', $id_modulo)
            ->where('c.id', $id_convocatoria)
            ->where('cxu.estado', 1)
            ->where('u.id', $id_usuario)
            ->whereNull('ru.id')
            ->orderBy('p.id', 'desc')
            ->limit(10)
            ->get();
    
    }
}