<?php

namespace App\Services;

use App\Repositories\EncabezadoRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EncabezadoService
{
    protected $encabezadoRepository;

    public function __construct(EncabezadoRepository $encabezadoRepository)
    {
        $this->encabezadoRepository = $encabezadoRepository;
    }

    /**
     * Registra un nuevo encabezado.
     *
     * @param array $data
     * @return \App\Models\Encabezado
     */
    public function createEncabezado(array $data)
    {
        $validator = Validator::make($data, [
            'id_modulo' => 'required|exists:modulos,id',
            'texto' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->encabezadoRepository->create($data);
    }
}
