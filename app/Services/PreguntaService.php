<?php

namespace App\Services;

use App\Repositories\ConvocatoriaRepository;
use App\Repositories\ModuloRepository;
use App\Repositories\PreguntaRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

class PreguntaService
{
    protected $preguntaRepository;
    protected $convocatoriaRepository;
    protected $moduloRepository;

    public function __construct(PreguntaRepository $preguntaRepository, ConvocatoriaRepository $convocatoriaRepository, ModuloRepository $moduloRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
        $this->convocatoriaRepository = $convocatoriaRepository;
        $this->moduloRepository = $moduloRepository;
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
            'id_convocatoria' => 'required|integer',
            'id_usuario' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $preguntas_flat = $this->preguntaRepository->getPreguntasByModuloAndUser(
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

    public function createBulk(array $data): array
    {
        // 1. Validar el formato de los datos recibidos.
        $validator = Validator::make($data, [
            '*.encabezados' => 'required|string',
            '*.data' => 'required|array',
            '*.data.*.preguntas' => 'required|string',
            '*.data.*.opciones' => 'required|array|min:1',
            '*.data.*.opciones.*.opcion' => 'required|string',
            '*.data.*.opciones.*.descripcion_opcion' => 'required|string',
            '*.data.*.opciones.*.correcta' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return [
                'message' => ['success' => false, 'message' => $validator->errors()],
                'status' => 400
            ];
        }

        try {
            $this->preguntaRepository->createBulk($data);
            return [
                'message' => ['success' => true, 'message' => 'Preguntas registradas masivamente con éxito.'],
                'status' => 201
            ];
        } catch (\Exception $e) {
            return [
                'message' => ['success' => false, 'message' => 'Error al registrar las preguntas: ' . $e->getMessage()],
                'status' => 500
            ];
        }
    }

    public function getPreguntasByConvocatoria(int $convocatoriaId, ?int $moduloId = null)
    {
        $convocatoria  = $this->convocatoriaRepository->getConvocatoriaById($convocatoriaId);
        $moduloQuery  = $this->moduloRepository->getModuloByIdConvocatoria($convocatoriaId);

        if ($moduloId) {
            $moduloQuery->where('id', $moduloId);
        }

        $modulo = $moduloQuery
            ->with([
                // Encabezados ordenados por id
                'encabezados' => function ($q) {
                    $q->select('id','texto','id_modulo')->orderBy('id');
                },
                // Preguntas ordenadas por id
                'encabezados.preguntas' => function ($q) {
                    $q->select('id','pregunta','id_encabezado')->orderBy('id');
                },
                // Opciones ordenadas por letra (a, b, c)
                'encabezados.preguntas.opciones' => function ($q) {
                    $q->select('id','id_pregunta','opcion','descripcion_opcion','correcta')
                    ->orderBy('opcion');
                },
            ])
            ->select('id','nombre','id_convocatoria')
            ->firstOrFail();

        // 3) Armar el payload EXACTO
        $payload = [
            "message" => "Preguntas obtenidas exitosamente.",
            "data" => [
                "convocatoria" => [
                    "id_convocatoria" => $convocatoria->id,
                    "nombre"          => $convocatoria->nombre,
                ],
                "modulo" => [
                    "id_modulo" => $modulo->id,
                    "nombre"    => $modulo->nombre,
                ],
                "data" => $modulo->encabezados->map(function ($en) {
                    return [
                        "encabezado" => [
                            "id_encabezado" => $en->id,
                            // OJO: en BD el campo es 'texto'
                            "encabezado"    => $en->texto,
                        ],
                        "preguntas" => $en->preguntas->map(function ($pr) {
                            return [
                                "pregunta" => [
                                    "id_pregunta" => $pr->id,
                                    "pregunta"    => $pr->pregunta,
                                ],
                                "opciones" => $pr->opciones->map(function ($op) {
                                    return [
                                        "opcion"             => $op->opcion,
                                        "descripcion_opcion" => $op->descripcion_opcion,
                                        "correcta"           => (bool) $op->correcta,
                                    ];
                                })->values(),
                            ];
                        })->values(),
                    ];
                })->values(),
            ],
        ];

        return response()->json($payload);
    }

}