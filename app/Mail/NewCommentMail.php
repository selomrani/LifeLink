<?php

namespace App\Mail;

use App\Models\BloodRequestPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $postOwner,
        public Comment $comment,
        public BloodRequestPost $post
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Someone commented on your LifeLink post',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-comment',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
