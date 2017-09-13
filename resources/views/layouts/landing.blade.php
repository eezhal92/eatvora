<!doctype html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="AMWidAJpiKvHnK2xMZcbwv2pJmlfnukUWjweeBS3rLc" />
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://fontlibrary.org/face/poppins" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="{{ mix('css/style.css') }}">
  </head>
  <body>
    @yield('content')

    <script src="{{ mix('/js/manifest.js') }}"></script>
    <script src="{{ mix('/js/landing.js') }}"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-105977759-1', 'auto');
      ga('send', 'pageview');

    </script>
  </body>
</html>
