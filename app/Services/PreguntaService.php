<?php

namespace App\Services;

use App\Repositories\PreguntaRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

class PreguntaService
{
    protected $preguntaRepository;

    public function __construct(PreguntaRepository $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    /**
     * Registra una nueva pregunta.
     *
     * @param array $data
     * @return \App\Models\Pregunta
     */
    public function createPregunta(array $data)
    {
        $validator = Validator::make($data, [
            'id_encabezado' => 'required|exists:encabezados,id',
            'pregunta' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->preguntaRepository->create($data);
    }

    /**
     * Obtiene y formatea las preguntas para un módulo y usuario específicos.
     *
     * @param array $data
     * @return array
     */
    public function getFormattedPreguntas(array $data)
    {
        $validator = Validator::make($data, [
            'id_modulo' => 'required|integer',
            'id_convocatoria' => 'required|integer',
            'id_usuario' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $preguntas_flat = $this->preguntaRepository->getPreguntasByModuloAndUser(
            $data['id_modulo'],
            $data['id_convocatoria'],
            $data['id_usuario']
        );

        // Si no se encuentran preguntas, se devuelve un array vacío
        if ($preguntas_flat->isEmpty()) {
            return [
                'convocatoria' => null,
                'modulo' => null,
                'data' => [],
            ];
        }

        $firstItem = $preguntas_flat->first();

        $formattedData = [
            'convocatoria' => [
                'id_convocatoria' => $firstItem->id_convocatoria,
                'nombre' => $firstItem->convocatoria_nombre,
            ],
            'modulo' => [
                'id_modulo' => $firstItem->id_modulo,
                'nombre' => $firstItem->modulo_nombre,
            ],
            'data' => []
        ];

        // Agrupa las preguntas y opciones
        $preguntasAgrupadas = $preguntas_flat->groupBy('id_pregunta');

        foreach ($preguntasAgrupadas as $preguntaId => $opciones) {
            $primeraOpcion = $opciones->first();
            $preguntaData = [
                'encabezado' => [
                    'id_encabezado' => $primeraOpcion->id_encabezado,
                    'encabezado' => $primeraOpcion->encabezado,
                ],
                'preguntas' => [
                    'id_pregunta' => $primeraOpcion->id_pregunta,
                    'pregunta' => $primeraOpcion->pregunta,
                ],
                'opciones' => $opciones->map(function ($opcion) {
                    return [
                        'opcion' => $opcion->opcion,
                        'descripcion_opcion' => $opcion->descripcion_opcion,
                        'correcta' => (bool)$opcion->correcta,
                    ];
                })->values()->all(),
            ];
            $formattedData['data'][] = $preguntaData;
        }

        return $formattedData;
    }
}