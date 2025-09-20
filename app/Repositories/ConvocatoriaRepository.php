<?php

namespace App\Repositories;

use App\Models\Convocatoria;
use App\Models\ConvocatoriaUsuario;
use App\Models\RespuestaUsuario;
use Illuminate\Support\Facades\DB;

class ConvocatoriaRepository
{
    /**
     * Obtiene todas las convocatorias.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Convocatoria::all();
    }

    /**
     * Crea una nueva convocatoria.
     *
     * @param array $data
     * @return Convocatoria
     */
    public function create(array $data)
    {
        return Convocatoria::create($data);
    }

    /**
     * Lista todas las convocatorias, con opción de búsqueda por nombre.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(string $query = '')
    {
        $queryBuilder = Convocatoria::query();

        $queryBuilder->where(function ($q) use ($query) {
            $q->whereRaw(
                "public.unaccent(lower(nombre)) LIKE '%' || public.unaccent(lower(?)) || '%'",
                [$query]
            )
            ->orWhereRaw(
                "public.unaccent(lower(etiqueta)) LIKE '%' || public.unaccent(lower(?)) || '%'",
                [$query]
            );
        });

        return $queryBuilder->get();
    }

    public function getConvocatoriaById($convocatoriaId)
    {   
        return Convocatoria::select('id','nombre')->findOrFail($convocatoriaId);
    }

    public function getConvocatoriaByUser(int $convocatoriaId, int $userId)
    {
        return Convocatoria::where('id', $convocatoriaId)
            ->whereHas('usuarios', function ($q) use ($userId) {
                $q->where('usuarios.id', $userId);
            })
            ->firstOrFail();
    }

    public function validarActivacionConvocatoria(int $convocatoriaId, int $userId)
    {
        return ConvocatoriaUsuario::where('id_convocatoria', $convocatoriaId)
            ->where('id_usuario', $userId)
            ->first();
    }

    public function registrarUsuarioConvocatoria(int $convocatoriaId, int $userId)
    {
        ConvocatoriaUsuario::create([
            'id_convocatoria' => $convocatoriaId,
            'id_usuario'      => $userId,
            'estado'          => false,
        ]);

        return ConvocatoriaUsuario::where('id_convocatoria', $convocatoriaId)
        ->where('id_usuario', $userId)
        ->first();
    }

    public function contarRespuestasPorConvocatoria(int $convocatoriaId, int $userId)
    {
        return RespuestaUsuario::where('id_usuario', $userId)
        ->whereIn('id_pregunta', function($query) use ($convocatoriaId) {
            $query->select('pre.id')
                  ->from('preguntas as pre')
                  ->join('encabezados as en', 'pre.id_encabezado', '=', 'en.id')
                  ->join('modulos as m', 'en.id_modulo', '=', 'm.id')
                  ->where('m.id_convocatoria', $convocatoriaId);
        })
        ->count();
    }

    public function getConvocatoriasByUsuario(int $userId) {
        return DB::table('convocatoria_x_usuarios as cxu')
            ->join('convocatorias as c', 'cxu.id_convocatoria', '=', 'c.id')
            ->where('cxu.id_usuario', $userId)
            ->select('cxu.id_convocatoria', 'cxu.id_usuario', 'c.codigo as codigo_convocatoria', 'c.nombre as nombre_convocatoria')
            ->get();
    }

    public function getRespuestasByConvocatoria(int $userId, int $convocatoriaId) {
        
        $preguntasUsuario = DB::table('respuesta_usuarios as ru')
            ->join('preguntas as p', 'ru.id_pregunta', '=', 'p.id')
            ->join('encabezados as e', 'p.id_encabezado', '=', 'e.id')
            ->join('modulos as m', 'e.id_modulo', '=', 'm.id')
            ->where('ru.id_usuario', $userId)
            ->where('m.id_convocatoria', $convocatoriaId)
            ->select(
                'e.id as id_encabezado',
                'e.texto as encabezado_texto',
                'p.id as id_pregunta',
                'p.pregunta as pregunta_texto',
                'ru.respuesta_usuario',
                'ru.correcta as respuesta_usuario_correcta'
            )
            ->get();
        // Agrupar las preguntas por encabezado para estructurar el JSON
        $groupedByEncabezado = $preguntasUsuario->groupBy('id_encabezado');
        $data = [];
        foreach ($groupedByEncabezado as $idEncabezado => $preguntas) {
            $encabezado = [
                'id_encabezado' => $idEncabezado,
                'encabezado' => $preguntas->first()->encabezado_texto,
            ];
            $preguntasArray = [];
            foreach ($preguntas as $pregunta) {
                // Obtener las opciones para cada pregunta
                $opciones = DB::table('opciones')
                    ->where('id_pregunta', $pregunta->id_pregunta)
                    ->select('opcion', 'descripcion_opcion', 'correcta')
                    ->get();
                $preguntasArray[] = [
                    'pregunta' => [
                        'id_pregunta' => $pregunta->id_pregunta,
                        'pregunta' => $pregunta->pregunta_texto,
                    ],
                    'opciones' => $opciones,
                    'respuesta_usuario' => $pregunta->respuesta_usuario,
                    'respuesta_usuario_correcta' => (bool)$pregunta->respuesta_usuario_correcta,
                ];
            }
            $data[] = [
                'encabezado' => $encabezado,
                'preguntas' => $preguntasArray,
            ];
        }
        $convocatoria = DB::table('convocatorias')->where('id', $convocatoriaId)->first();
        $modulo = DB::table('modulos as m')
            ->join('encabezados as e', 'm.id', '=', 'e.id_modulo')
            ->join('preguntas as p', 'e.id', '=', 'p.id_encabezado')
            ->join('respuesta_usuarios as ru', 'p.id', '=', 'ru.id_pregunta')
            ->where('m.id_convocatoria', $convocatoriaId)
            ->select('m.id as id_modulo', 'm.nombre')
            ->first();
        return [
            'convocatoria' => [
                'id_convocatoria' => $convocatoria->id,
                'nombre' => $convocatoria->nombre,
            ],
            'modulo' => [
                'id_modulo' => $modulo->id_modulo,
                'nombre' => $modulo->nombre,
            ],
            'data' => $data,
        ];
    }
  
}