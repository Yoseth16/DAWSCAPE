<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoAccesoInscripcion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Crea una instancia del Mailable.
     */
    public function __construct(
        public Inscripcion $inscripcion,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎮 Tu código de acceso a DAWSCAPE',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.codigo-acceso',
            with: [
                'inscripcion' => $this->inscripcion,
                'nombreEquipo' => $this->inscripcion->nombre_equipo,
                'codigoAcceso' => $this->inscripcion->codigo_acceso,
            ],
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
