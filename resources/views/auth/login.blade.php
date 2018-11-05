@extends('layouts.main', ['bodyClass' => 'login-page'])

@section('content')
    <div class="login-wrapper">
        <div class="user-panel">
            <h1 class="user-panel-title">{{ __('auth.login') }}</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group mb-lg {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="form-label" for="email">{{ __('auth.email') }}</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group mb-lg {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="form-label" for="password">{{ __('auth.password') }}</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group mb-lg">
                    <input type="checkbox" class="checkbox-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="checkbox"></span>
                    {{ __('auth.remember_me') }}
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">
                        {{ __('auth.login') }}
                    </button>
                    <a class="btn btn-default" href="{{ route('password.request') }}">
                        {{ __('auth.forgot_password') }}
                    </a>
                </div>

                @if(env('DEMO_USER'))
                <div class="alert alert-info">
                    Username: <b>{{ env('DEMO_USER') }}</b><br>
                    Password: <b>{{ env('DEMO_PASS') }}</b><br>
                </div>
                @endif
            </form>
        </div>
    </div>
@endsection
