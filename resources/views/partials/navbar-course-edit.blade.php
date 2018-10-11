<nav class="navbar registration-navbar justify-content-between m-0 fixed-top">
    <a class="navbar-brand">
        <img class="mr-lg-4" src="/images/2.svg" alt="Orangogo Logo">
        <div class="registration-navbar--vertical-line d-inline-block"></div>
        <p class="d-inline-block mb-0 ml-lg-4 font-weight-bold">Registrazione Corso</p>
    </a>

    <go-to-dashboard-button
            :companyprop="{{$company}}"
            @if(isset($course))
                :courseprop="{{$course}}"
                :action="'edit'"
            @else
                :action="'create'"
            @endif>
    </go-to-dashboard-button>
</nav>