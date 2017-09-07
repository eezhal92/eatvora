<header class="navigation {{ session()->has('notif') ? 'navigation--has-notif' : '' }}">
  <div class="container container--small">
    <nav class="navigation__nav clearfix">
      <div class="navigation__logo">
        <a href="{{ url('/') }}" class="navigation__logo-typo">eatvora</a>
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
      <button id="mobile-sidebar-open-trigger" class="pull-right sidebar__btn hidden-sm hidden-md hidden-lg">
        <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 0h24v24H0z" fill="none" />
          <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="white" />
        </svg>
      </button>
      <div class="sidebar">
        <div class="sidebar__overlay"></div>
        <div class="sidebar__content">
          <div class="sidebar__header">
            <button id="mobile-sidebar-close-trigger" class="pull-right sidebar__btn hidden-sm hidden-md hidden-lg">
              <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <line x1="4" y1="20" x2="20" y2="4" stroke="#424242" strokeWidth="2" />
                <line x1="4" y1="4" x2="20" y2="20" stroke="#424242" strokeWidth="2" />
              </svg>
            </button>
          </div>
          <ul class="sidebar__nav">
            @if (Route::has('login'))
              @if (Auth::check())
                <li><a href="{{ url('/home') }}">Beranda</a></li>
                <li><a href="{{ url('/logout') }}">Log Out</a></li>
              @else
                <li><a href="{{ url('/') }}">Beranda</a></li>
                <li><a target="_blank" href="http://blog.eatvora.com">Blog</a></li>
                <li><a href="{{ url('/login') }}">Log In</a></li>
              @endif
            @endif
          </ul>
        </div>
      </div>
    </nav>
  </div>
</header>
