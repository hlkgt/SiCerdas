<x-mail::message>
# Hello {{$username}}

your account already approved by your principle school, please login and verify your account, and enjoy using <p style="display:inline-block;font-size: 16px;font-weight:800;">{{ config('app.name') }}</p>

<x-mail::button :url="$url" color="primary">
Click To Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
