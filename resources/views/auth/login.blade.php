@extends('layouts.main')
@section('title', 'Log in')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="user-login"><!---->
                    <h3 class="font-color-orange text-center text-uppercase">Effettua il login</h3>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div id="" role="group" class="b-form-group form-group is-valid"><!---->
                            <div class="">

                                <input id="email" type="email"
                                       class=" form-input form-control{{ $errors->has('email') ? ' is-danger' : '' }}"
                                       name="email" value="{{ old('email') }}" placeholder="Indirizzo email"
                                       aria-required="true"
                                       aria-invalid="true"
                                       autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display:block">
                <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div id="" role="group" class="b-form-group form-group is-valid"><!---->
                            <div class="">
                                <input id="loginPassword" name="password" placeholder="Password"
                                       class="form-input form-control {{ $errors->has('password') ? ' is-danger' : '' }}"
                                       aria-required="true"
                                       aria-invalid="true"
                                       autocomplete="off" type="password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display:block">
                <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--<div id="" role="group" class="b-form-group form-group">
                            <div class="">
                                <div class="form-input custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" value="true"
                                           name="remember" {{ old('remember') ? 'checked' : '' }}><label
                                            for="loginRemember" class="custom-control-label"> {{ __('Ricordami') }}
                                    </label>
                                </div>
                            </div>
                        </div>-->
                        <button type="submit" class="btn orange-solid-button text-capitalize btn-default btn-block">
                            Accedi
                        </button>
                    </form>
                    <a href="{{ route('password.request') }}" class="user-login__forgot-password">Password
                        dimenticata?</a>

                    <div class="user-login__divider"><span>oppure</span></div>
                    <div class="user-login__social-buttons"><a href="/google-redirect" target="_self"
                                                               class="btn btn--google btn-default">
                            <div class="btn--google__content"><img src="/images/google.svg" class="">
                                Google
                            </div>
                        </a> <a href="/facebook-redirect" target="_self" class="btn btn--facebook btn-default">
                            <div class="btn--facebook__content"><img src="/images/facebook.svg" class="">
                                Facebook
                            </div>
                        </a></div>
                    <div class="user-login__divider user-login__divider--no-text m-t-50"></div>

                    <p class="user-login__register text-center">Non hai un account? <a href="{{route('register')}}">Registrati</a>
                    </p></div>

            </div>
        </div>
    </div>
@endsection
