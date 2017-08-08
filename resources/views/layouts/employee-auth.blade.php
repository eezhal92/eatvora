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
    @stack('head')
  </head>
  <body style="background: #f3f3f3">
    @yield('content')
  </body>
</html>
