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
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://www.workwork.my/" />
        <meta property="og:image" content="http://www.workwork.my/images/logo.png" />
        <meta property="og:locale" content="ms_MY" />
        <meta property="og:locale:alternate" content="en_GB" />
        <meta property="og:site_name" content="WorkWork" />

        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}" media="screen" charset="utf-8">
        <link href='https://fonts.googleapis.com/css?family=Josefin+Slab:700' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="images/favicon.png" type="image/png">
        @yield('header')
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

        @yield('content')

        <footer>
            <p class="text-xs-center m-b-3">
                <small class="copyright">© Workwork, Pocket Pixel, Sdn Bhd. 2016.</small>
            </p>
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-77927366-1', 'auto');
          ga('send', 'pageview');

        </script>
        @yield('footer')

    </body>
</html>
