@extends('layouts.landing')

@section('title', 'eatvora - Your office lunch solution')

@section('content')

  <section id="hero">
    <div class="overlay"></div>
    <div class="container container--small">
      <div class="row">

        <div class="hero__content">
          <div class="col-xs-12 col-sm-6">

            <h2 class="headline headline--white">Have problem with your office lunch?</h2>
            <p class="description secondary-text secondary-text--white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia cum!</p>

          </div>

          <div class="col-xs-12 col-sm-offset-1 col-sm-5">

            <div class="form-condensed hero__form">
              <h3 class="form-condensed__heading form-condensed__heading--white">Find it more</h3>
              <form class="form-condensed" action="" method="post">
                <input class="form-condensed__input" type="text" name="name" placeholder="Name" autocomplete="off">
                <input class="form-condensed__input" type="text" name="email" placeholder="Email" autocomplete="off">
                <input class="form-condensed__input" type="text" name="company" placeholder="Company" autocomplete="off">
                {{ csrf_field() }}
                <button class="form-condensed__button btn btn--primary">Submit <i class="fa fa-chevron-right form-condensed__chevron"></i></button>
              </form>
            </div>
        </div>

        </div>
      </div>
    </div>
  </section>

  <section id="how-it-works">
    <div class="container container--small">

      <div class="row">
        <div class="col-xs-12">
        <h3 class="secondary-headline text-center" style="margin-bottom: 60px">It's so simple. How it works</h3>
        </div>
        <div class="col-xs-12 col-sm-offset-2 col-sm-8">
          <p class="secondary-text text-center secondary-text--gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
        </div>
      </div>

      <div class="row">
        <div class="steps">
          <div class="col-xs-12 col-sm-4">
            <div class="steps__item">
              <div class="steps__img-wrapper">
                <img src="{{ url('/images/tray.png') }}" class="img-responsive steps__img" alt="">
              </div>
              <h4 class="steps__heading">Make Plan</h4>
              <p class="secondary-text  steps__item-desc secondary-text--small secondary-text--gray text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="steps__item">
              <div class="steps__img-wrapper">
                <img src="{{ url('/images/chef.png') }}" class="img-responsive steps__img" alt="">
              </div>
              <h4 class="steps__heading">Checkout</h4>
              <p class="secondary-text steps__item-desc secondary-text--small secondary-text--gray text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="steps__item">
              <div class="steps__img-wrapper">
                <img src="{{ url('/images/cutlery.png') }}" class="img-responsive steps__img" alt="">
              </div>
              <h4 class="steps__heading">Enjoy It!</h4>
              <p class="secondary-text  steps__item-desc secondary-text--small secondary-text--gray text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <section id="why-us">

    <div class="benefits__plate"></div>

    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-offset-5 col-sm-7">
          <h3 class="secondary-headline benefits__headline">Why using us?</h3>

          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Lotsa Choice</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Healthy</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Fair point system</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Halal</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis cum odit doloribus quibusdam minima, iste nulla placeat.</p>
              </div>
            </div>
          </div>

          <a href="#" class="btn btn--secondary benefits__btn">Find It More</a>
        </div>
      </div>
    </div>
  </section>

  <section id="featured-meals">

    <div class="featured-meals__spoon"></div>

    <div class="container container--small">
      <div class="row featured-meals__row">
        <div class="col-xs-12 col-sm-3">
          <h3 class="section-heading section-heading--dark featured-meals__heading">Featured Meals</h3>
        </div>
        <div class="col-xs-12 col-sm-6">
          <p class="secondary-text secondary-text--small secondary-text--dark">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae nobis animi enim at ex! Excepturi libero ab saepe, numquam sequi ullam. Ipsa aut molestiae placeat eum, veniam debitis inventore accusantium.</p>
        </div>
        <div class="col-xs-12 col-sm-3">
          <div class="text-center">
            <a href="#" class="btn btn--dark-outline btn--lg">See our featured meals</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="clients">
    <div class="container container--small">
      <div class="row">
        <div class="col-xs-12 col-sm-4">
          <h3 class="section-heading section-heading--white clients__heading">They Like Us</h3>
          <p class="secondary-text secondary-text--small secondary-text--white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae nobis animi enim at ex! Excepturi libero ab saepe, numquam sequi ullam. Ipsa aut molestiae placeat eum.</p>
          <div class="clients__action">
            <a href="#" class="btn btn--outline clients__btn">Request Demo</a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-offset-1 col-sm-7">
          <div class="clients__logos">
            <div class="clients__logo-row">
              <div class="clients__logo-item">
                <img src="{{ url('/images/shirokuma.png') }}" alt="">
              </div>
              <div class="clients__logo-item">
                <img src="{{ url('/images/gunasland.png') }}" alt="">
              </div>
              <div class="clients__logo-item">
                <img src="{{ url('/images/sscx.png') }}" alt="">
              </div>
            </div>
            <div class="clients__logo-row">
              <div class="clients__logo-item">
                <img src="{{ url('/images/duitpintar.png') }}" alt="">
              </div>
              <div class="clients__logo-item">
                <img src="{{ url('/images/midtrans.png') }}" alt="">
              </div>
              <div class="clients__logo-item">
                <img src="{{ url('/images/cashlez.png') }}" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="form-footer">

    <div class="overlay"></div>

    <section id="form-bottom">
      <div class="container container--small">
        <div class="row">
          <div class="col-xs-12 col-sm-offset-3 col-sm-6">
            <div class="contact-form">
              <div class="form-condensed">
                <h3 class="form-condensed__heading form-condensed__heading--white">Find it more</h3>
                <form class="form-condensed" action="" method="post">
                  <input class="form-condensed__input" type="text" name="name" placeholder="Name" autocomplete="off">
                  <input class="form-condensed__input" type="text" name="email" placeholder="Email" autocomplete="off">
                  <input class="form-condensed__input" type="text" name="company" placeholder="Company" autocomplete="off">
                  <textarea row="6" class="form-condensed__textarea" type="text" name="message" placeholder="Message" autocomplete="off"></textarea>
                  {{ csrf_field() }}
                  <button class="form-condensed__button btn btn--primary">Submit <i class="fa fa-chevron-right form-condensed__chevron"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @include('footer')
  </section>
@endsection
