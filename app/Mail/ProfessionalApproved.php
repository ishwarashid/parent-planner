<?php

namespace App\Mail;

use App\Models\Professional;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfessionalApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * Using a public property makes the $professional object automatically available in the view.
     */
    public function __construct(
        public Professional $professional,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Your Profile has been Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // This points to the Blade view that will be the email's content.
        return new Content(
            view: 'emails.professionals.approved',
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
