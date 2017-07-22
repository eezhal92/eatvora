<div class="col-lg-12">
  <div class="eatvora-header" style="padding-top: 40px">
    <div class="page-header__title" style="position: absolute; height: 32px">
      <h1 style="font-size: 20px; margin: 0; padding: 5px 0">{{ $header }}</h1>
    </div>
    <div class="top-nav">
      <div class="top-nav__search-bar">
        <form action="">
          <input type="text" class="form-control" placeholder="Search..." style="height: 32px; border-radius: 32px;">
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
        <a href="{{ url('/logout') }}" style="margin-top: 5px; height: 32px; display: inline-block;padding: 0 10px;">Logout</a>
      </div>
    </div>
  </div>
</div>
