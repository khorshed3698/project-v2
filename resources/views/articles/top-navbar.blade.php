<div class="row">
    <div class="col-md-12 top-navbar">
        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    {{--                        <a class="navbar-brand visible-xs" href="">Home</a>--}}
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav hover-nav">
                        <li><a href="{{ url('/login') }}">Home</a></li>
                        <li><a href="{{ url('articles/bida') }}" class="{{ (Request::is('articles/bida') ? 'active' : '') }}">About BIDA</a></li>
                        <li><a href="{{ url('articles/available-services') }}" class="{{ (Request::is('articles/available-services') ? 'active' : '') }}">Available Services</a></li>
                        <li><a href="{{ url('articles/support') }}" class="{{ (Request::is('articles/support') ? 'active' : '') }}">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
