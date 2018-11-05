
@component('mail::message')
# Account created

An account created in {{ config('app.name') }}, with the following password:
@component('mail::panel')
Password: **{{ $password }}**
@endcomponent

@component('mail::button', ['url' => config('app.url') . '/login'])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

