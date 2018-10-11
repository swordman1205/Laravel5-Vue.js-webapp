@extends('layouts.main')
@section('title', 'Reset password')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="user-login"><!---->
                    <h3 class="font-color-orange text-center text-uppercase">Reimposta la password</h3>
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="b-form-group form-group is-valid">

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{ $email ?? old('email') }}" required autofocus placeholder="Email">

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="b-form-group form-group is-valid">

                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" required placeholder="Scegli una password">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="b-form-group form-group is-valid">
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required placeholder="Ripeti la password">
                        </div>

                        <button type="submit" class="btn orange-solid-button text-capitalize btn-default btn-block">
                            {{ __('Reset password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
