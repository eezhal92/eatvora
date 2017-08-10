<div class="col-lg-12">
  <div class="eatvora-header">
    <div class="eatvora-header__page-title">
      <h1 class="eatvora-header__page-heading">{{ $header }}</h1>
    </div>
    <div class="top-nav">
      <div class="top-nav__search-bar">
        <form action="">
          <input type="text" class="form-control top-nav__search-input" placeholder="Search...">
        </form>
      </div>
      <div class="top-nav__profile">
        <div class="profile-menu">
          <div class="profile-menu__img-container">
            <img src="http://lorempixel.com/32/32/people" class="img-circle" alt="">
          </div>
        </div>
      </div>
      <div>
        <a href="{{ url('/logout') }}" class="top-nav__link">Logout</a>
      </div>
    </div>
  </div>
</div>
