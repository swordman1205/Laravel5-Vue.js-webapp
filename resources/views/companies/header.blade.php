<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <b-nav class="w-100 border-bottom">
        <b-navbar-brand>
            <div class="d-inline-block p-2">
                <img class="mr-lg-4" src="/images/2.svg" alt="Ornagogo Logo">
                <div class="registration-navbar--vertical-line d-inline-block"></div>
                <span class="d-inline-block ml-lg-4 font-weight-bold text-uppercase">La tua dashboard</span>
            </div>
        </b-navbar-brand>
        <b-navbar class="ml-auto">
            <b-nav-text class="font-weight-bold">{{$user->first_name}} {{$user->last_name}}</b-nav-text>
            <b-nav-item class="mr-4"><i class="far fa-user"></i></b-nav-item>
        </b-navbar>
    </b-nav>
    <b-container fluid>
        <div>
            @yield('content')
        </div>
    </b-container>
</div>
<script>
    window.baseUrl = '{{env('APP_URL')}}'
    window.googleMapsAPI = '{{env('GOOGLE_MAPS_API')}}'
</script>
</body>
</html>
