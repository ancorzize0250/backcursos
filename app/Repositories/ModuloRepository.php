<?php

namespace App\Repositories;

use App\Models\Modulo;

class ModuloRepository
{
    /**
     * Obtiene todos los modulos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Modulo::all();
    }

    /**
     * Crea un nuevo modulo.
     *
     * @param array $data
     * @return Modulo
     */
    public function create(array $data)
    {
        return Modulo::create($data);
    }

    public function getModuloByIdConvocatoria($convocatoriaId)
    {   
        return Modulo::where('id_convocatoria', $convocatoriaId);
    }
}
