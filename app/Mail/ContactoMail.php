<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $correo;
    public $mensaje;

    /**
     * Create a new message instance.
     */
    public function __construct($nombre, $correo, $mensaje)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->mensaje = $mensaje;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($this->correo, $this->nombre),
            subject: 'Nuevo Mensaje de Contacto',
        );
    }

    /**
     * Get the message content.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contacto', // Esto usará el archivo 'resources/views/emails/contacto.blade.php'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}