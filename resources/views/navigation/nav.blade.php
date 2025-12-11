<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0px;">
    @include ('navigation.topbar')
    @if (!in_array(Auth::user()->user_type, ['5x505']))
        @include ('navigation.sidebar')
    @endif
</nav>
