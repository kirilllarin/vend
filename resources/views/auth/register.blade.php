@extends('layouts.main')

@section('content')
<div class="container">
    <div class="user-panel">
        <h1 class="user-panel-title">{{ __('main.register') }}</h1>

        @include('partials.errors')

        <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">Név</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">Szervezet neve</label>
                <input id="name" type="text" class="form-control" name="company" value="{{ old('company') }}" required>
            </div>


            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email">E-mail cím</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password">Jelszó</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <label for="password-confirm">Jelszó megerősítése</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">
                    {{ __('main.register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
