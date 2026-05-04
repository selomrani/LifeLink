<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $donor,
        public Donation $donation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your donation offer was accepted',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donation-accepted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
