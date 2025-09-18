<?php

namespace App\Http\Controllers;

use App\Services\CorreoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CorreoController extends Controller
{
    protected $correoService;

    public function __construct(CorreoService $correoService)
    {
        $this->correoService = $correoService;
    }

    public function enviar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error de validación en los datos.'
            ], 422); // Código de error 422
        }

        $nombre = $request->input('name');
        $correo = $request->input('email');
        $mensaje = $request->input('message');

        $enviado = $this->correoService->enviarCorreoContacto($nombre, $correo, $mensaje);

        if ($enviado) {
            return response()->json([
                'status' => 'success',
                'message' => 'Se ha enviado el correo exitosamente'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al enviar el correo'
            ], 500); // Código de error 500
        }
    }
}