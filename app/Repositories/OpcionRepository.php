<?php

namespace App\Repositories;

use App\Models\Opcion;
use Illuminate\Support\Collection;

class OpcionRepository
{
    /**
     * Crea múltiples opciones en la base de datos.
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function createMany(array $data): Collection
    {
        $opciones = [];
        foreach ($data as $opcionData) {
            $opciones[] = Opcion::create($opcionData);
        }
        return collect($opciones);
    }
}
