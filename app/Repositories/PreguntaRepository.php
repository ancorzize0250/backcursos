<?php

namespace App\Repositories;

use App\Models\Pregunta;
use App\Models\Encabezado;
use App\Models\Opcion;
use App\Models\Convocatoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
    public function getPreguntasByModuloAndUser(int $id_convocatoria, int $id_usuario)
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
            ->where('c.id', $id_convocatoria)
            ->where('cxu.estado', 1)
            ->where('u.id', $id_usuario)
            ->whereNull('ru.id')
            ->orderBy('p.id', 'desc')
            ->limit(10)
            ->get();
    
    }

     /**
     * Realiza la inserción masiva en la base de datos.
     *
     * @param array $payload
     * @return void
     */
    public function createBulk(array $payload): void
    {
        DB::beginTransaction();

        try {
            foreach ($payload as $grupo) {
                // Crear el encabezado.
                $encabezado = Encabezado::create([
                    'texto' => $grupo['encabezados'],
                    'id_modulo' => $grupo['id_modulo'],
                ]);

                // Recorrer las preguntas dentro de este grupo de encabezado.
                foreach ($grupo['data'] as $datosPregunta) {
                   
                    $pregunta = Pregunta::create([
                        'pregunta' => $datosPregunta['preguntas'],
                        'id_encabezado' => $encabezado->id,
                    ]);

                    // Recorrer las opciones de la pregunta y crearlas.
                    foreach ($datosPregunta['opciones'] as $datosOpcion) {
                        Opcion::create([
                            'opcion' => $datosOpcion['opcion'],
                            'descripcion_opcion' => $datosOpcion['descripcion_opcion'],
                            'correcta' => $datosOpcion['correcta'],
                            'id_pregunta' => $pregunta->id,
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene los encabezados con sus preguntas y opciones para una convocatoria.
     *
     * @param int $convocatoriaId
     * @return Collection
     */
    public function getOrganizedQuestionsData(int $convocatoriaId): Collection
    {
        return Encabezado::whereHas('modulo', function ($query) use ($convocatoriaId) {
            $query->where('id_convocatoria', $convocatoriaId);
        })
        ->with(['preguntas' => function ($query) {
            $query->with('opciones');
        }])
        ->get();
    }

    /**
     * Obtiene la información de la convocatoria y su módulo.
     *
     * @param int $convocatoriaId
     */
    public function getConvocatoriaAndModulo(int $convocatoriaId)
    {
       return Convocatoria::with([
            'modulos.encabezados.preguntas.opciones'
        ])->findOrFail($convocatoriaId);
    }
}