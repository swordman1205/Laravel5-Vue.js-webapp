<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content=" {{ csrf_token()}} ">

    @include('partials.head')

    <script>
        window.Laravel = {csrfToken: '{{ csrf_token() }}'};
        window.baseUrl = '{{env('APP_URL')}}';
        window.googleMapsAPI = '{{env('GOOGLE_MAPS_API')}}';
        window.currentUserId = '{{!is_null($user = Auth::user()) ? $user->id : null}}';
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.js"></script>

    <title>{{ MetaTag::get('title') }} | Orangogo</title>
    <meta name="title" content="{{MetaTag::get('title')}}">

    {!! MetaTag::tag('description',
        'Trova sul Motore di ricerca dello Sport OrangoGo tutte le attività sportive vicine a te. Scegli lo sport o la Società sportiva, prenota  la prima prova gratuita e inizia a scoprire il tuo sport.') !!}
    {!! MetaTag::tag('image', asset('images/2.svg')) !!}
    {!! MetaTag::tag('robots') !!}

    {!! MetaTag::openGraph() !!}
    <meta property="og:title" content="{{MetaTag::get('title')}} | Orangogo">

    {!! MetaTag::twitterCard() !!}

<!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <link href="//code.jquery.com/ui/1.12.1/themes/sunny/jquery-ui.css" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">

    <!-- Script -->
    @yield('script')
</head>
<body>

<div id="app">
    <div class="container-fluid">
        @if(!isset($navbarHidden) && !isset($navbarToInclude))
            @include('partials.navbar',["nav_active" => (isset($nav_active) ? $nav_active : -1)])
        @endif

        @if(isset($navbarToInclude))
            @include("partials.$navbarToInclude")
        @endif

        @if (Route::currentRouteName() != 'login')
            @include('modals.login')
        @endif
        @if (Route::currentRouteName() != 'register')
            @include('modals.register')
        @endif

        <div class="page-content @if(isset($grey)) page-content__grey @endif">
            @yield('content')
        </div>

        @include('partials.footer')


    </div>
</div>
<script src=" {{ asset('js/app.js') }} "></script>

<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/4661896.js"></script>
<!-- End of HubSpot Embed Code -->
<script type="text/javascript" src="/js/modernizr-custom.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/cookiechoice.js"></script>

<script>
    if (!Modernizr.inputtypes.date) {
        $('input[type=date]').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    }
</script>

<script>
    (function (h, o, t, j, a, r) {
        h.hj = h.hj || function () {
            (h.hj.q = h.hj.q || []).push(arguments)
        };
        h._hjSettings = {hjid: 771720, hjsv: 6};
        a = o.getElementsByTagName('head')[0];
        r = o.createElement('script');
        r.async = 1;
        r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
        a.appendChild(r);
    })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
</script>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-105414160-1', 'auto');
    ga('send', 'pageview');

</script>

<script>//<![CDATA[
    document.addEventListener('DOMContentLoaded', function(event) {
        cookieChoices.showCookieConsentBar('I cookie ci aiutano a fornire i nostri servizi. Utilizzando tali servizi, accetti l\'utilizzo dei cookie da parte nostra.',
            'Ok', 'Informazioni',
            '/privacy');
    });
    //]]></script>

@yield('scripts')

@if (session()->has('success'))
    <script>
        window.alerts.success('{{session()->get('success')}}');
    </script>
@endif
@if (session()->has('error'))
    <script>
        window.alerts.error('{{session()->get('error')}}');
    </script>
@endif
</body>
</html>
