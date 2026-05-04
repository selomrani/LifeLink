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
