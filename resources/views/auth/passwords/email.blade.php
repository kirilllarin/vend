@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="user-panel">
            <a href="{{ url('/') }}" class="logo"></a>
            <h1 class="user-panel-title">Password reset</h1>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">
                        Send Password Reset Link
                    </button>
                    <div class="pull-left">
                        <a href="{{ url('/login') }}" class="btn btn-link">Back to login</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
