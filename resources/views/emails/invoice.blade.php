@component('mail::message')
# Your order has completed

Thank you for using {{ config('app.name') }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent

