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
