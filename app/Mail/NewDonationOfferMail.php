<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDonationOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $postOwner,
        public Donation $donation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Someone offered to donate to your LifeLink post',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-donation-offer',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
