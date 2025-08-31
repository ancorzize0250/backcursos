<?php

namespace App\Services;

use App\Repositories\OpcionRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OpcionService
{
    protected $opcionRepository;

    public function __construct(OpcionRepository $opcionRepository)
    {
        $this->opcionRepository = $opcionRepository;
    }

    /**
     * Registra múltiples opciones para una pregunta.
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function createOpciones(array $data)
    {
        $validator = Validator::make($data, [
            '*.id_pregunta' => 'required|exists:preguntas,id',
            '*.opcion' => 'required|string|max:255',
            '*.descripcion_opcion' => 'required|string|max:255',
            '*.correcta' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->opcionRepository->createMany($data);
    }
}