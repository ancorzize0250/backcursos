<?php

namespace App\Services;

use App\Mail\ContactoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CorreoService
{
    /**
     * Envía un correo de contacto.
     *
     * @param string $nombre
     * @param string $correo
     * @param string $mensaje
     * @return bool
     */
    public function enviarCorreoContacto($nombre, $correo, $mensaje)
    {
        try {
            Mail::to('ancorzize@gmail.com')->send(new ContactoMail($nombre, $correo, $mensaje));
            return true;
        } catch (\Exception $e) {
            Log::error("Error al enviar correo: " . $e->getMessage()); 
            return false;
        }
    }
}