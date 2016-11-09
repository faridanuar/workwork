<nav class="navbar ww-navbar navbar-fixed-top">

        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">@lang('navigation.toggle')</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img alt="WorkWork" src="/images/logo-workwork.png" height="25" width="196" class="hidden-sm">
                    <img alt="WorkWork" src="/images/logo-symbol.png" height="25" class="visible-sm-inline">
                </a>
            </div>
            <!-- global searchbox -->
            <div class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control" id="global-search" />
                </div>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!-- <li><a href="{{ url('/home') }}">Home</a></li> -->
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @can('edit_advert')
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww navbar-btn-ww--cta" href="{{ url('/adverts/create') }}">@lang('navigation.create')</a></li>
                    @endcan

                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww" href="{{ url('/plans') }}">@lang('navigation.price')</a></li>
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww" href="{{ url('/login') }}">@lang('navigation.login')</a></li>
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww" href="{{ url('/register') }}">@lang('navigation.signup')</a></li>
                        <li><a class="btn btn-default navbar-btn navbar-btn-ww navbar-btn-ww--cta" href="{{ url('/register') }}">@lang('navigation.create')</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/account">Account</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>@lang('navigation.logout')</a></li>
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
                    <a href="/dashboard" class="auth-nav-item" aria-selected="{{ set_active('dashboard') }}">@lang('navigation.dashboard')</a>
                </li>
                @can('view_my_adverts')
                    @if(Auth::user()->employer)
                        <li>
                            <a href="/adverts" class="auth-nav-item" aria-selected="{{ set_active('adverts') }}">@lang('navigation.your_adverts')</a>
                        </li>
                        <li>
                            <a href="/company/{{ Auth::user()->employer->id }}/{{ Auth::user()->employer->business_name }}" class="auth-nav-item" aria-selected="{{ set_active('company/' . Auth::user()->employer->id . '/' . Auth::user()->employer->business_name)}}">@lang('navigation.company_profile')</a>
                        </li>
                    @endif
                @endcan
                @can('edit_info')
                    @if(Auth::user()->jobSeeker)
                        <li>
                            <a href="/profile/{{ Auth::user()->jobSeeker->id }}" class="auth-nav-item" aria-selected="false" aria-selected="{{ set_active('profile/' . Auth::user()->jobSeeker->id) }}">@lang('navigation.your_profile')</a>
                        </li>
                        <li>
                            <a href="/my/applications" class="auth-nav-item" aria-selected="{{set_active('my/applications')}}">@lang('navigation.my_applications')</a>
                        </li>
                    @endif  
                @endcan
                {{--<li>
                    <a href="" class="auth-nav-item" aria-selected="{{set_active('')}}">@lang('navigation.account')</a>
                </li>--}}
                <li>
                    <a href="/" class="auth-nav-item" aria-selected="{{set_active('/')}}">Home</a>
                </li>
            </ul>
        </div>
    </nav>
    @endif