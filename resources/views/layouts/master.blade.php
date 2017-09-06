<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Eatvora')</title>

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @include('scripts.app')
  </head>
  <body class="bg-dark">
    <div id="app">
      @yield('body')
    </div>

    @stack('beforeScripts')
    <script src="{{ elixir('js/app.js') }}"></script>
    @stack('afterScripts')
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
