@extends('layouts.main')
@section('title', 'Register')
@section('content')

    <div class="user-login">
        <p class="user-login__register mb-0">Accedi con <a href="/google-redirect">Google</a> o <a
                    href="/facebook-redirect">Facebook</a></p>
        <div class="user-login__divider pt-3 pb-3"></div>
        <form method="POST" action="{{ route('register') }}" id="register-form">
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

                    <input id="first_name" type="text"
                           class=" form-input form-control{{ $errors->has('first_name') ? ' is-danger' : '' }}"
                           name="first_name" value="{{ old('first_name') }}" placeholder="Nome"
                           aria-required="true"
                           aria-invalid="true"
                           autocomplete="off">
                    @if ($errors->has('first_name'))
                        <span class="invalid-feedback" style="display:block">
                <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>
            <div id="" role="group" class="b-form-group form-group is-valid"><!---->
                <div class="">

                    <input id="last_name" type="text"
                           class=" form-input form-control{{ $errors->has('last_name') ? ' is-danger' : '' }}"
                           name="last_name" value="{{ old('last_name') }}" placeholder="Cognome"
                           aria-required="true"
                           aria-invalid="true"
                           autocomplete="off">
                    @if ($errors->has('last_name'))
                        <span class="invalid-feedback" style="display:block">
                <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>
            <div id="" role="group" class="b-form-group form-group is-valid"><!---->
                <div class="">
                    <input id="password" name="password" placeholder="Password"
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
            <div id="" role="group" class="b-form-group form-group is-valid"><!---->
                <div class="">
                    <input id="loginPassword" name="password_confirmation" placeholder="Conferma Password"
                           class="form-input form-control {{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                           aria-required="true"
                           aria-invalid="true"
                           autocomplete="off" type="password">

                    @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback" style="display:block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>
            <p class="font-weight-bold mb-1">DATA DI NASCITA</p>
            <div class="d-flex justify-content-between mb-2">
                <div class="flex-fill mr-2">
                    <div class="">
                        <select id="registerDayOfBirthDate" name="birth-date"
                                class="form-input form-control custom-select" aria-required="true"
                                aria-invalid="true">
                            <option value="">Giorno</option>
                            <?php for($i = 1;$i <= 31; $i++): ?>
                            <option value="{{$i}}">{{$i}}</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="flex-fill mx-2">
                    <div class=""><select id="registerMonthOfBirthDate" name="birth-month"
                                          class="form-input form-control custom-select" aria-required="true"
                                          aria-invalid="true">
                            <option value="">Mese</option>
                            <option value="0">Gennaio</option>
                            <option value="1">Febbraio</option>
                            <option value="2">Marzo</option>
                            <option value="3">Aprile</option>
                            <option value="4">Maggio</option>
                            <option value="5">Giugno</option>
                            <option value="6">Luglio</option>
                            <option value="7">Agosto</option>
                            <option value="8">Settembre</option>
                            <option value="9">Ottobre</option>
                            <option value="10">Novembre</option>
                            <option value="11">Dicembre</option>
                        </select>
                    </div>
                </div>
                <div class="flex-fill ml-2">
                    <div class=""><select id="registerYearOfBirthDate" name="birth-year"
                                          class="form-input form-control custom-select" aria-required="true"
                                          aria-invalid="true">
                            <option value="">Anno</option>
                            <?php for($i = date('Y');$i >= 1950; $i--): ?>
                            <option value="{{$i}}">{{$i}}</option>
                            <?php endfor; ?>
                        </select></div>
                </div>
            </div>
            <div class="">
                <input type="hidden" name="birthday">
                @if ($errors->has('birthday'))
                    <span class="invalid-feedback" style="display:block">
                <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                @endif
            </div>
            <div id="" role="group" class="b-form-group form-group">
                <div class="">
                    <div class="form-input custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" required id="acceptance"
                        ><label
                                for="acceptance" class="custom-control-label"> Accetto i <a
                                    href="/terms" target="_blank">termini di servizio</a> e la <a
                                    href="/privacy" target="_blank">privacy policy</a>
                        </label>
                    </div>
                </div>
            </div>
            <b-btn class="orange-gradient-button text-capitalize" variant="default" block type="submit">
                Registrati
            </b-btn>
        </form>
        <div class="user-login__divider pt-3 pb-2"></div>
        <p class="user-login__register">Hai già un account? <a href="{{route('login')}}">Effettua il login</a></p>
    </div>
@endsection
@section('scripts')
    <script>
        $('#register-form').submit(function (e) {
            e.preventDefault();
            var birthday = new moment().year($("[name='birth-year']").val()).month($("[name='birth-month']").val());
            console.log(birthday);
            if (birthday.daysInMonth() >= $("[name='birth-date']").val()) {
                birthday.date($("[name='birth-date']").val());
                $('input[name="birthday"]').val(birthday.format('DD/MM/YYYY'));
                this.submit();
            } else {
                window.alerts.error('You selected an invalid date!');
                return;
            }

        });
    </script>
@endsection


