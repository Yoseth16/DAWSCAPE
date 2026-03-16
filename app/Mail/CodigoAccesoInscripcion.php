<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoAccesoInscripcion extends Mailable
{
    use Queueable, SerializesModels;

<<<<<<< HEAD
    public Inscripcion $inscripcion;
    public string $nombreEquipo;
    public string $codigoAcceso;

    /**
     * Crea una nueva instancia del Mailable.
     */
    public function __construct(Inscripcion $inscripcion)
    {
        $this->inscripcion   = $inscripcion;
        $this->nombreEquipo  = $inscripcion->nombre_equipo;
        $this->codigoAcceso  = $inscripcion->codigo_acceso;
    }

    /**
     * Asunto del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Tu código de acceso para DAWSCAPE — ' . $this->nombreEquipo,
        );
    }

    /**
     * Vista y datos del correo.
     */
=======
    public function __construct(
        public Inscripcion $inscripcion
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->inscripcion->correo],
            subject: 'DAWSCAPE - Tu Código de Acceso al Escape Room',
        );
    }

>>>>>>> b47905c9862b4b51080cbd80099d8f009c996617
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.codigo-acceso',
            with: [
<<<<<<< HEAD
                'nombreEquipo' => $this->nombreEquipo,
                'codigoAcceso' => $this->codigoAcceso,
=======
                'nombreEquipo' => $this->inscripcion->nombre_equipo,
                'codigoAcceso' => $this->inscripcion->codigo_acceso,
>>>>>>> b47905c9862b4b51080cbd80099d8f009c996617
                'inscripcion'  => $this->inscripcion,
            ],
        );
    }
<<<<<<< HEAD

    /**
     * Sin adjuntos.
     */
    public function attachments(): array
    {
        return [];
    }
=======
>>>>>>> b47905c9862b4b51080cbd80099d8f009c996617
}
