<?php

namespace App\Mail;

use App\Models\MonetaryDonation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonetaryDonationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $postOwner,
        public MonetaryDonation $monetaryDonation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You received a monetary donation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.monetary-donation-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
