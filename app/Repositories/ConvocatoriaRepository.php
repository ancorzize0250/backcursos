<?php

namespace App\Repositories;

use App\Models\Convocatoria;

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

        if (!empty($query)) {
            $queryBuilder->where('nombre', 'LIKE', "%{$query}%");
        }

        return $queryBuilder->get();
    }
}