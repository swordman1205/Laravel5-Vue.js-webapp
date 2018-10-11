<Navbar is-guest="{{ Auth::guest() }}"
        :user="{{ Auth::user() ?Auth::user(): "{}" }}"
        @if(Auth::user() && Auth::user()->hasCompany())
                :currentcompany="@yield('current-company', "'Default'")"
                :companies="{{Auth::user()->companies}}"
        @endif
        route="{{Route::currentRouteName()}}"
        :nav_activeprop="{{ $nav_active }}"
        title="@yield('navbar-title')">
</Navbar>