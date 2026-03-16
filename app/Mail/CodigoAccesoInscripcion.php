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
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.codigo-acceso',
            with: [
                'nombreEquipo' => $this->nombreEquipo,
                'codigoAcceso' => $this->codigoAcceso,
                'inscripcion'  => $this->inscripcion,
            ],
        );
    }

    /**
     * Sin adjuntos.
     */
    public function attachments(): array
    {
        return [];
    }
}
