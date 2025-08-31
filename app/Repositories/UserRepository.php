<?php

namespace App\Repositories;

use App\Models\Usuario;

class UserRepository
{
    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param array $data
     * @return Usuario
     */
    public function create(array $data): Usuario
    {
        return Usuario::create($data);
    }
}
