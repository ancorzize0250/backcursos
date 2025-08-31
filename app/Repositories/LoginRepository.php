<?php

namespace App\Repositories;

use App\Models\Login;

class LoginRepository
{
    /**
     * Crea un nuevo registro de login.
     *
     * @param array $data
     * @return Login
     */
    public function create(array $data): Login
    {
        return Login::create($data);
    }

    /**
     * Busca un login por correo.
     *
     * @param string $correo
     * @return \App\Models\Login|null
     */
    public function findByEmail(string $correo)
    {
        return Login::where('correo', $correo)->first();
    }
}
