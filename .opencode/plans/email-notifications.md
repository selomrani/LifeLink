# Email Notifications Implementation Plan

## New Files to Create (10 total)

### 1. `app/Mail/NewDonationOfferMail.php`
```php
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
```

### 2. `app/Mail/DonationAcceptedMail.php`
```php
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
```

### 3. `app/Mail/DonationRejectedMail.php`
```php
<?php
namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $donor,
        public Donation $donation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your donation offer was not accepted',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donation-rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
```

### 4. `app/Mail/MonetaryDonationConfirmationMail.php`
```php
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
```

### 5. `app/Mail/MonetaryDonationReceivedMail.php`
```php
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
```

### 6. `resources/views/emails/new-donation-offer.blade.php`
```blade
<x-mail::message>
# New donation offer on your post, {{ $postOwner->firstname }}!

**{{ $donation->donor->firstname }} {{ $donation->donor->lastname }}** has offered to donate blood for your request.

- **Blood Type Needed:** {{ $donation->post->blood_type }}
- **Location:** {{ $donation->post->location }}
- **Needed By:** {{ $donation->post->needed_by }}

Log in to LifeLink to review and accept their offer.

<x-mail::button :url="config('app.url')">
View Post
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
```

### 7. `resources/views/emails/donation-accepted.blade.php`
```blade
<x-mail::message>
# Your donation offer was accepted, {{ $donor->firstname }}!

The post owner **{{ $donation->post->user->firstname }} {{ $donation->post->user->lastname }}** has accepted your donation offer.

- **Blood Type Needed:** {{ $donation->post->blood_type }}
- **Location:** {{ $donation->post->location }}
- **Needed By:** {{ $donation->post->needed_by }}

Please coordinate with the post owner to arrange the donation. Your generosity is truly saving lives.

<x-mail::button :url="config('app.url')">
View Post
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
```

### 8. `resources/views/emails/donation-rejected.blade.php`
```blade
<x-mail::message>
# Your donation offer was not accepted, {{ $donor->firstname }}.

Thank you for offering to donate blood for the request **{{ $donation->post->blood_type }}** in {{ $donation->post->location }}.

The post owner has decided to proceed with another donor this time, but your willingness to help is deeply appreciated. There are many other posts on LifeLink where your donation could make a difference.

<x-mail::button :url="config('app.url')">
Browse Other Posts
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
```

### 9. `resources/views/emails/monetary-donation-confirmation.blade.php`
```blade
<x-mail::message>
# Thank you for your donation, {{ $donor->firstname }}!

Your monetary donation of **${{ number_format($monetaryDonation->amount, 2) }}** has been successfully processed.

- **Post:** {{ $monetaryDonation->post->blood_type }} blood request in {{ $monetaryDonation->post->location }}
- **Amount:** ${{ number_format($monetaryDonation->amount, 2) }}

Your contribution makes a real difference. Thank you for supporting LifeLink.

<x-mail::button :url="config('app.url')">
View Post
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
```

### 10. `resources/views/emails/monetary-donation-received.blade.php`
```blade
<x-mail::message>
# You received a monetary donation, {{ $postOwner->firstname }}!

**{{ $monetaryDonation->user->firstname }} {{ $monetaryDonation->user->lastname }}** has made a monetary donation of **${{ number_format($monetaryDonation->amount, 2) }}** for your post.

- **Amount:** ${{ number_format($monetaryDonation->amount, 2) }}
- **Post:** {{ $monetaryDonation->post->blood_type }} blood request in {{ $monetaryDonation->post->location }}

<x-mail::button :url="config('app.url')">
View Post
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
```

---

## Files to Modify (3 total)

### 11. `app/Http/Controllers/DonationsController.php`

Add `use` statements at top:
```php
use App\Mail\NewDonationOfferMail;
use App\Mail\DonationAcceptedMail;
use App\Mail\DonationRejectedMail;
use Illuminate\Support\Facades\Mail;
```

**`offerDonation()`** — add after `Donation::create()`:
```php
$post->load('user');
Mail::to($post->user->email)->send(new NewDonationOfferMail($post->user, $donation));
```

**`acceptDonation()`** — add after `$donation->save()`:
```php
$donation->load('donor');
Mail::to($donation->donor->email)->send(new DonationAcceptedMail($donation->donor, $donation));
```

**`rejectDonation()`** — add after `$donation->save()`:
```php
$donation->load('donor');
Mail::to($donation->donor->email)->send(new DonationRejectedMail($donation->donor, $donation));
```

### 12. `app/Http/Controllers/Donations/StripeController.php`

Add `use` statements at top:
```php
use App\Mail\MonetaryDonationConfirmationMail;
use App\Mail\MonetaryDonationReceivedMail;
use Illuminate\Support\Facades\Mail;
```

**`donate()`** — add after `MonetaryDonation::create()`:
```php
$monetaryDonation = MonetaryDonation::create([...]);

$bloodrequest->load('user');
$donor = Auth::user();
Mail::to($donor->email)->send(new MonetaryDonationConfirmationMail($donor, $monetaryDonation));
Mail::to($bloodrequest->user->email)->send(new MonetaryDonationReceivedMail($bloodrequest->user, $monetaryDonation));
```

### 13. `app/Http/Controllers/Admin/AdminController.php`

**`ban()`** — add `Mail::to($user->email)->send(new BannedMail($user));` after `$user->save()` (line 48)

---

## Summary of Email Flow After Implementation

| Trigger | Recipient | Mailable |
|---|---|---|
| User registers | New user | `WelcomeMail` |
| New comment on post | Post owner | `NewCommentMail` |
| Donation offered | Post owner | `NewDonationOfferMail` |
| Donation accepted | Donor | `DonationAcceptedMail` |
| Donation rejected | Donor | `DonationRejectedMail` |
| Monetary donation | Donor | `MonetaryDonationConfirmationMail` |
| Monetary donation | Post owner | `MonetaryDonationReceivedMail` |
| Admin bans (direct) | Banned user | `BannedMail` |
| Admin toggle-ban | Banned/unbanned user | `BannedMail` / `UnbannedMail` |
| Admin reviews report | Banned user | `BannedMail` |
| Forgot password | User | Laravel built-in |
