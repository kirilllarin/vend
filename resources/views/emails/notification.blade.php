
@component('mail::message')
# Order Shipped

{{ $model->related->title }}

@component('mail::button', ['url' => ''])
View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

