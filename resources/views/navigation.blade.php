<header class="navigation {{ session()->has('notif') ? 'navigation--has-notif' : '' }}">
  <div class="container container--small">
    <nav class="navigation__nav clearfix">
      <div class="navigation__logo">
        <a href="#" class="navigation__logo-typo">eatvora</a>
      </div>
      <ul class="navigation__main-nav pull-right hidden-xs">
        @if (Route::has('login'))
            @if (Auth::check())
                <li><a href="{{ url('/home') }}">Beranda</a></li>
                <li><a href="{{ url('/logout') }}">Log Out</a></li>
            @else
                <li><a target="_blank" href="http://blog.eatvora.com">Blog</a></li>
                <li><a href="{{ url('/login') }}">Log In</a></li>
            @endif
        @endif
      </ul>
    </nav>
  </div>
</header>
