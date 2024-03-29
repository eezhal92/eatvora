  <div class="navbar">
    <div class="container container--small">
      <div class="navbar__container">
        <div class="navbar__logo">
          <a href="{{ url('/') }}" rel="nofollow">
            eatvora
          </a>
        </div>
        <ul class="navbar__menu hidden-xs">
          <li><a href="/home">Beranda</a></li>
          <li>
            <a href="/meals">Katalog</a>
          </li>
        </ul>
        <ul class="navbar__menu navbar__menu--right hidden-xs">
          <li>
            <a href="/cart" class="cart-container">
              <cart-count></cart-count>
            </a>
            <a id="balance" href="#">{{ auth()->user()->balance() / config('eatvora.rupiah_per_point') }} Poin</a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle avatar-container" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <img src="http://lorempixel.com/32/32" class="img-circle avatar avatar--not-present" />
              <div data-icon="default-user" class="default-avatar">
                @include('employee.default-avatar')
              </div>
            </a>
            <ul class="dropdown-menu" aria-labelledby="account-btn">
              <li class="dropdown-menu__username">
                <a href="{{ url('/profile') }}">
                  <span style="font-size: 10px; display: block;">{{ auth()->user()->name }}</span>
                  Profile
                </a>
              </li>
              <li role="separator" class="divider"></li>
              <li><a href="/logout">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
