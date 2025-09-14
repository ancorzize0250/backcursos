<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Registra un nuevo usuario y su login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // 1. Validación de datos
        $validator = Validator::make($request->all(), [
            'identificacion' => 'required|string|max:255|unique:usuarios,identificacion',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:logins,correo',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 2. Separar datos de usuario y login
        $userData = $request->only('identificacion', 'nombres', 'apellidos');
        $loginData = $request->only('correo', 'password');

        // 3. Llamar al servicio
        $result = $this->authService->registerUser($userData, $loginData);

        if ($result['success']) {
            return response()->json($result, 201);
        }

        return response()->json($result, 500);
    }

     /**
     * Autentica a un usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $response = $this->authService->login([
                'correo' => $request->correo,
                'password' => $request->password,
            ]);
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
