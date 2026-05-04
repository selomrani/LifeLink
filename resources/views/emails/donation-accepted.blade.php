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
