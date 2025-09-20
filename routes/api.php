<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\EncabezadoController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\OpcionController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespuestaUsuarioController;
use App\Http\Controllers\CorreoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Ruta para registrar un nuevo usuario
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/convocatorias', [ConvocatoriaController::class, 'index']);
Route::post('/convocatorias', [ConvocatoriaController::class, 'store']);

Route::get('/modulos', [ModuloController::class, 'index']);
Route::post('/modulos', [ModuloController::class, 'store']);

Route::post('/encabezado', [EncabezadoController::class, 'store']);

Route::post('/preguntas', [PreguntaController::class, 'store']);
Route::get('/preguntas', [PreguntaController::class, 'getQuestions']);
Route::post('/preguntasmasivo', [PreguntaController::class, 'storeBulk']);

Route::post('/respuestas', [RespuestaUsuarioController::class, 'store']);
Route::get('/respuestas', [RespuestaUsuarioController::class, 'history']);

Route::post('/opciones', [OpcionController::class, 'store']);

Route::post('/correo', [CorreoController::class, 'enviar']);

Route::get('/convocatoriaxusuario', [ConvocatoriaController::class, 'getConvocatoriasByUsuario']);
Route::get('/historico', [ConvocatoriaController::class, 'getRespuestas']);

