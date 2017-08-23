  <div class="navbar">
    <div class="container container--small">
      <div class="navbar__container">
        <div class="navbar__logo">
          <a href="#logo" rel="nofollow">
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
            <a href="/cart">
              <cart-count></cart-count>
            </a>
            <a id="balance" href="#">Rp. {{ number_format(auth()->user()->balance()) }}</a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" id="account-btn" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <img src="http://lorempixel.com/32/32" class="img-circle" />
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
