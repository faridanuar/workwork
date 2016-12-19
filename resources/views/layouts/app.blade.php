<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="fb:app_id" content="1578992975731734" />
    <meta property="fb:admins" content="622633523" />
    <meta property="og:title" content="WorkWork - portal pencarian kerja part-time" />
    <meta property="og:description" content="Jika anda mencari kerja part-time atau sedang mencari pekerja part-time, di sinilah tempatnya! #kerjakerjakerja" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.workwork.my/" />
    <meta property="og:image" content="http://www.workwork.my/images/fb-image.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:locale" content="ms_MY" />
    <meta property="og:locale:alternate" content="en_GB" />
    <meta property="og:site_name" content="WorkWork" />


    <title>WorkWork - portal pencarian kerja part-time</title>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}" media="screen" charset="utf-8">
    
    <!-- Algolia Stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">

    @yield('js_stylesheets')
</head>

@if (Auth::user())
<body id="@yield('body-id')" class="auth-body">
@else
<body id="@yield('body-id')">
@endif

    @include('layouts.navigation')

    <div class="container">
        @yield('content')
    </div>

    <div class="container">
        <!-- @include('footer') -->
    </div>

    @include('scripts')
    <script src="{{ elixir('js/app.js') }}"></script>
    @include('js_plugins.algolia_global')
    @yield('js_plugins')
</body>
</html>
