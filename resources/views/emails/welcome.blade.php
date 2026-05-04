<x-mail::message>
# Welcome to LifeLink, {{ $user->firstname }}!

Thank you for joining LifeLink. Your registration was successful.

Here are your account details:

- **Name:** {{ $user->firstname }} {{ $user->lastname }}
- **Email:** {{ $user->email }}
- **Blood Type:** {{ $user->bloodType->name ?? 'Not set' }}

You can now browse blood donation requests and help save lives by offering to donate.

<x-mail::button :url="config('app.url')">
Go to LifeLink
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
