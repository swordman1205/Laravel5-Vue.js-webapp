@extends('layouts.main')
@section('title', 'Forgot password')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="user-login"><!---->
                    <h3 class="font-color-orange text-center text-uppercase">Recupera la password</h3>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

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
                        <button type="submit" class="btn orange-solid-button text-capitalize btn-default btn-block">
                            {{ __('Invia link per resettare la password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
