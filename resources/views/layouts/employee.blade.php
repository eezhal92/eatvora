<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Eatvora')</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @include('scripts.app')
    @stack('head')
  </head>
  <body>
    <div id="app">
      @include('employee.navbar')
      @yield('body')
    </div>

    <footer style="padding: 40px">
      <div class="container container--small">
        <div class="row">
          <p >&copy; {{ date('Y') }} - eatvora.com</p>
        </div>
      </div>
    </footer>

    @stack('beforeScripts')
    <script src="{{ mix('/js/manifest.js') }}"></script>
    <script src="{{ mix('/js/vendor.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    @stack('afterScripts')
  </body>
</html>
