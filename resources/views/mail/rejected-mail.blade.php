<x-mail::message>
# Hello {{$username}}

Your account rejected from school principle, but your account already deleted, then you can create new account using bellow button

<x-mail::button :url="$url" color="error">
Create New Account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
