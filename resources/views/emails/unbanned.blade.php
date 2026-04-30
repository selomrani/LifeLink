<x-mail::message>
# Your account has been reinstated, {{ $user->firstname }}

Good news! Your LifeLink account has been reviewed and your suspension has been lifted. You can now log in and use the platform again.

**Account:** {{ $user->email }}

<x-mail::button :url="config('app.url')">
Go to LifeLink
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
