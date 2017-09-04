@extends('layouts.landing')

@section('title', 'eatvora - Your office lunch solution')

@section('content')
  @if (session()->has('notif'))
  <div class="notif notif--{{ session('notif-type') }}">
    {{ session('notif') }}
  </div>
  @endif

  @include('navigation')

  <section id="hero">
    <div class="overlay"></div>
    <div class="container container--small">
      <div class="row">

        <div class="hero__content">
          <div class="col-xs-12 col-sm-6">

            <h2 class="headline headline--white">Kelola makan siang kantor tanpa ribet</h2>
            <p class="description secondary-text secondary-text--white">Beragam menu makan siang yang dapat dipilih dan rencanakan  sesuai selera</p>

          </div>

          <div class="col-xs-12 col-sm-offset-1 col-sm-5">

            <div class="form-condensed hero__form">
              <h3 class="form-condensed__heading form-condensed__heading--white">Cari Tahu Lebih Lanjut</h3>
              <form class="form-condensed" action="{{ url('/landing-form') }}" method="post">
                <input class="form-condensed__input" type="text" name="name" placeholder="Nama" autocomplete="off">
                <input class="form-condensed__input" type="text" name="email" placeholder="Email" autocomplete="off">
                <input class="form-condensed__input" type="text" name="company" placeholder="Perusahaan" autocomplete="off">
                {{ csrf_field() }}
                <button class="form-condensed__button btn btn--primary">Kirim <i class="fa fa-chevron-right form-condensed__chevron"></i></button>
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
        <h3 class="secondary-headline text-center" style="margin-bottom: 60px">Sangat simple, begini langkahnya</h3>
        </div>
      </div>

      <div class="row">
        <div class="steps">
          <div class="col-xs-12 col-sm-4">
            <div class="steps__item">
              <div class="steps__img-wrapper">
                <img src="{{ url('/images/tray.png') }}" class="img-responsive steps__img" alt="">
              </div>
              <h4 class="steps__heading">Susun Rencana Makan Siang</h4>
              <p class="secondary-text  steps__item-desc secondary-text--small secondary-text--gray text-center">Tentukan menu makan siang untuk minggu depan, pilih sesuai selera dari beragam menu yang tersedia</p>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="steps__item">
              <div class="steps__img-wrapper">
                <img src="{{ url('/images/chef.png') }}" class="img-responsive steps__img" alt="">
              </div>
              <h4 class="steps__heading">Checkout</h4>
              <p class="secondary-text steps__item-desc secondary-text--small secondary-text--gray text-center">Sesudah menyusun rencana makan siang, tinggal lakukan checkout agar untuk reservasi.</p>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="steps__item">
              <div class="steps__img-wrapper">
                <img src="{{ url('/images/cutlery.png') }}" class="img-responsive steps__img" alt="">
              </div>
              <h4 class="steps__heading">Nikmati</h4>
              <p class="secondary-text  steps__item-desc secondary-text--small secondary-text--gray text-center">Kami akan mengantar makan siang ke kantor Anda, sesuai rencana yang telah dibuat. Selanjutnya, Anda tinggal menikmati. Mudah!</p>
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
          <h3 class="secondary-headline benefits__headline">Mengapa menggunakan Kami?</h3>

          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Variatif, Banyak Pilihan</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Makan siangmu, seleramu. Dengan beragam pilihan, tidak perlu harus seragam dengan makan siang rekan kantor yang lain.</p>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Fleksible</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Jadwal makan siang dapat diatur sesuai keinginan.</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Fair Point System</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Berapa jumlah yang Anda top-up, begitu pula jumlah hak Anda. Point Anda tidak memiliki masa kadaluarsa.</p>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="benefits__item">
                <h4 class="benefits__item-title">Halal</h4>
                <p class="secondary-text secondary-text--small secondary-text--gray benefits__desc">Semua makanan yang tersedia dijamin kehalalan-nya. Tak perlu kuatir!</p>
              </div>
            </div>
          </div>

          <a href="#find-it-more" class="btn btn--secondary benefits__btn">Cari Tahu Lebih Lanjut</a>
        </div>
      </div>
    </div>
  </section>

  <section id="featured-meals">

    <div class="featured-meals__spoon"></div>

    <div class="container container--small">
      <div class="row featured-meals__row">
        <div class="col-xs-12 col-sm-3">
          <h3 class="section-heading section-heading--dark featured-meals__heading">Approved Partner</h3>
        </div>
        <div class="col-xs-12 col-sm-6">
          <p class="secondary-text secondary-text--small secondary-text--dark">Seluruh partner Kami yang menyediakan menu makan siang telah di seleksi dan verifikasi terlebih dahulu. Penilaian dimulai dari kebersihan sampai pada pengerjaan makanan, sehingga menu yang ada pada katalog sesuai dengan standar Kami.</p>
        </div>
        <div class="col-xs-12 col-sm-3">
          <div class="text-center">
            <a href="#become-partner" class="btn btn--dark-outline btn--lg">Jadi Partner Kami</a>
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
                <h3 class="form-condensed__heading form-condensed__heading--white">Cari Tahu Lebih Lanjut</h3>
                <form class="form-condensed" action="{{ url('/landing-form') }}" method="post">
                  <input class="form-condensed__input" type="text" name="name" placeholder="Nama" autocomplete="off">
                  <input class="form-condensed__input" type="text" name="email" placeholder="Email" autocomplete="off">
                  <input class="form-condensed__input" type="text" name="company" placeholder="Perusahaan" autocomplete="off">
                  <textarea row="6" class="form-condensed__textarea" type="text" name="message" placeholder="Pesan" autocomplete="off"></textarea>
                  {{ csrf_field() }}
                  <button class="form-condensed__button btn btn--primary">Kirim <i class="fa fa-chevron-right form-condensed__chevron"></i></button>
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
