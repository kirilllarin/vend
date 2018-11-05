<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>{{ config('app.name') }}</title>
    <script>
        window._token = '{{ csrf_token() }}';
        window.baseUrl = '{{ url('/api') }}';
        var card_id = '{{ request()->get('card') }}';
    </script>
</head>
<body>

<div id="app">
    <main-nav></main-nav>
    <modal></modal>
    <router-view v-cloak></router-view>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
