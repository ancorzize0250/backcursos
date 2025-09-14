<?php

namespace App\Repositories;

use App\Models\Convocatoria;
use App\Models\ConvocatoriaUsuario;
use App\Models\RespuestaUsuario;

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

        $queryBuilder->whereRaw(
            "public.unaccent(lower(nombre)) LIKE '%' || public.unaccent(lower(?)) || '%'",
            [$query]
        );

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
  
}