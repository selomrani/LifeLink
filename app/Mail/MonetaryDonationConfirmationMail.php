<?php

namespace App\Mail;

use App\Models\MonetaryDonation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonetaryDonationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $donor,
        public MonetaryDonation $monetaryDonation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for your monetary donation to LifeLink',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.monetary-donation-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
