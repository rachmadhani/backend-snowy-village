<?php

namespace App\Mail;

use App\Models\Franchise;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Franchise $franchise,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Franchise Inquiry from ' . $this->franchise->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.franchise-inquiry',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
