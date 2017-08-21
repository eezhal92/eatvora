<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Eatvora Admin')</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    @include('scripts.app')
    @stack('head')
  </head>
  <body>
    <div id="app">
      @yield('content')

      <div class="eatvora-nav__container">
        <div class="eatvora-nav__panel">
          <a class="eatvora-nav__home-btn" href="{{ url('/ap/dashboard') }}">
            eatvora
          </a>
          <div class="eatvora-nav__contents">
            <ul class="eatvora-nav__menus">
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/dashboard') }}" class="eatvora-nav__menu-item {{ request()->checkForCss('/ap/dashboard', 'eatvora-nav__menu-item--active') }}">Dashboard</a>
                </div>
              </li>
              <li class="eatvora-nav__separator"></li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/companies') }}" class="eatvora-nav__menu-item {{ request()->checkForCss('/ap/companies', 'eatvora-nav__menu-item--active') }}">Company</a>
                </div>
              </li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/vendors') }}" class="eatvora-nav__menu-item">Vendor</a>
                </div>
              </li>
              <li class="eatvora-nav__separator"></li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/menus') }}" class="eatvora-nav__menu-item">Menu</a>
                </div>
              </li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/schedules') }}" class="eatvora-nav__menu-item">Schedule</a>
                </div>
              </li>
              <li class="eatvora-nav__separator"></li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/orders') }}" class="eatvora-nav__menu-item">Order</a>
                </div>
              </li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="{{ url('/ap/payments') }}" class="eatvora-nav__menu-item">Payment</a>
                </div>
              </li>
              <li class="eatvora-nav__separator"></li>
              <li>
                <div class="eatvora-nav__menu-wrapper">
                  <a href="#" class="eatvora-nav__menu-item">Holiday</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      @yield('modals')

    </div>

    @stack('beforeScripts')

    @stack('afterScripts')
  </body>
</html>
