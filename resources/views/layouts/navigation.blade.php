

<nav class="navbar ww-navbar navbar-fixed-top">

        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img alt="WorkWork" src="/images/logo-workwork.png" height="25" width="196">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!-- <li><a href="{{ url('/home') }}">Home</a></li> -->
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @can('edit_advert')
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww navbar-btn-ww--cta" href="{{ url('/adverts/create') }}">Create New Part-time Ad</a></li>
                    @endcan
                    @if(Auth::user())
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww" href="{{ url('/dashboard') }}">Dashboard</a></li>
                    @endif

                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww navbar-btn-ww--cta" href="{{ url('/plans') }}">Post part-time Ad</a></li>
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww" href="{{ url('/login') }}">Log In</a></li>
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww" href="{{ url('/register') }}">Sign Up</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="">Account</a></li>
                                <li><a href="{{ url('/plans') }}">Pricing</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Log out</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if (Auth::user())
    <nav class="auth-nav">
        <div class="container">
            <ul>
                <li>
                    <a href="/dashboard" class="auth-nav-item" aria-selected="{{ set_active('dashboard') }}">Dashboard</a>
                </li>
                @can('view_my_adverts')
                    <li>
                        <a href="/my/adverts" class="auth-nav-item" aria-selected="{{ set_active('my/adverts') }}">Your Adverts</a>
                    </li>
                @endcan
                @can('edit_company')
                        @if(Auth::user()->employer)
                        <li>
                            <a href="/company/{{ Auth::user()->employer->id }}/{{ Auth::user()->employer->business_name }}" class="auth-nav-item" aria-selected="{{ set_active('company/' . Auth::user()->employer->id . '/' . Auth::user()->employer->business_name)}}">Company Profile</a>
                        </li>
                    @endif
                @endcan
                @can('edit_info')
                    @if(Auth::user()->jobSeeker)
                        <li>
                            <a href="/profile/{{ Auth::user()->jobSeeker->id }}" class="auth-nav-item" aria-selected="false" aria-selected="{{ set_active('profile/' . Auth::user()->jobSeeker->id) }}">Your Profile</a>
                        </li>
                    @endif
                        <li>
                            <a href="/my/applications" class="auth-nav-item" aria-selected="{{set_active('my/applications')}}">My Applications</a>
                        </li>
                @endcan
                <li>
                    <a href="" class="auth-nav-item" aria-selected="{{set_active('')}}">Account</a>
                </li>
            </ul>
        </div>
    </nav>
    @endif