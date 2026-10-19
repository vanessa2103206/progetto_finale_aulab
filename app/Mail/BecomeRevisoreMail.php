<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class BecomeRevisoreMail extends Mailable
{
    use Queueable, SerializesModels;

    // Proprietà pubblica per passare i dati dell'utente alla vista email
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope (Oggetto della mail)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Richiesta di Candidatura come Revisore - Presto.it',
        );
    }

    /**
     * Get the message content definition (Puntiamo alla vista HTML della mail)
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.become_revisore',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
