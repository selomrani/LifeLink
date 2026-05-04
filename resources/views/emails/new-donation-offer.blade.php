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
