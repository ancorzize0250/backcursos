<?php

namespace App\Services;

use App\Repositories\LoginRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Usuario;

class AuthService
{
    protected $userRepository;
    protected $loginRepository;

    public function __construct(UserRepository $userRepository, LoginRepository $loginRepository)
    {
        $this->userRepository = $userRepository;
        $this->loginRepository = $loginRepository;
    }

    /**
     * Registra un nuevo usuario y su información de login.
     *
     * @param array $userData
     * @param array $loginData
     * @return array
     */
    public function registerUser(array $userData, array $loginData): array
    {
        try {
            DB::beginTransaction();

            // 1. Crear el usuario
            $user = $this->userRepository->create($userData);

            // 2. Encriptar la contraseña y crear el login
            $loginData['password'] = Hash::make($loginData['password']);
            $loginData['id_usuario'] = $user->id;
            $this->loginRepository->create($loginData);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Usuario registrado exitosamente.',
                'user' => $user,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al registrar el usuario: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Autentica a un usuario por correo y contraseña.
     *
     * @param array $credentials
     * @return array
     * @throws \Exception
     */
    public function login(array $credentials)
    {
        $login = $this->loginRepository->findByEmail($credentials['correo']);

        if (!$login || !Hash::check($credentials['password'], $login->password)) {
            throw new Exception('Credenciales incorrectas. Verifique su correo y contraseña.');
        }

        $usuario = Usuario::where('id', $login->id_usuario)->first();

        return [
            'message' => 'Login exitoso.',
            'usuario' => $usuario,
            'login' => $login,
            // Aquí puedes generar un token JWT o de sesión si lo necesitas
        ];
    }
}
