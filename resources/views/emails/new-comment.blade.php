<x-mail::message>
# New comment on your post, {{ $postOwner->firstname }}!

**{{ $comment->user->firstname }} {{ $comment->user->lastname }}** commented on your blood request post:

> {{ $comment->body }}

Log in to LifeLink to view and reply.

<x-mail::button :url="config('app.url')">
View Post
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
