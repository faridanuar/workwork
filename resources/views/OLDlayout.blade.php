<!DOCTYPE html>
<html>
    <head>
        <!-- Required meta tags always come first -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
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
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous"> -->
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}" media="screen" charset="utf-8">
        <link href='https://fonts.googleapis.com/css?family=Josefin+Slab:700' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="images/favicon.png" type="image/png">
        
    </head>
    <body>
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

        @include('navigation')
        
        <div class="container">
          @yield('content')
        </div>        
        
        <div class="container">
          @include('footer')
        </div>
        
        @include('scripts')

    </body>
</html>
