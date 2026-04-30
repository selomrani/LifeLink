<x-mail::message>
# Your account has been suspended, {{ $user->firstname }}

After reviewing a report submitted by another user, your LifeLink account has been suspended for violating our community guidelines.

**Account:** {{ $user->email }}

If you believe this was a mistake, please contact our support team.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
