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

    <!-- plugins -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <!-- Algolia plugins -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/places.js/1/places.min.js"></script>

    <!-- Dropzone plugins -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>

    <!-- Bootstrap-tagsinput plugins -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}" media="screen" charset="utf-8">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png">

    <!-- Algolia Stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">

    <!-- Dropzone Stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css">

    <!-- Bootstrap-tagsinput Stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">

</head>

@if (Auth::user())
<body id="@yield('body-id')" class="auth-body">
@else
<body id="@yield('body-id')">
@endif
    @include('layouts.navigation')
    @include('js_plugins.algolia_global')

    <div class="container">
        @yield('content')
    </div>

    <div class="container">
        <!-- @include('footer') -->
    </div>

    <!-- JavaScripts -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '1578992975731734',
          xfbml      : true,
          version    : 'v2.6'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>

    @include('scripts')

    <script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>
