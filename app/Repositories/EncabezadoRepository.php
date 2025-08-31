<?php

namespace App\Repositories;

use App\Models\Encabezado;

class EncabezadoRepository
{
    /**
     * Crea un nuevo encabezado en la base de datos.
     *
     * @param array $data
     * @return \App\Models\Encabezado
     */
    public function create(array $data)
    {
        return Encabezado::create($data);
    }
}
