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
