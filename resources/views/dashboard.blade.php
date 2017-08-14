@extends('layouts.employee')

@push('head')
  <style>
    .no-plan {
      text-align: center;
      padding: 40px;
    }

    .meal-day {
      margin-bottom: 20px;
    }

    .meal-day__date {
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .meal-item {
      overflow: hidden;
      margin-bottom: 16px;
      background: #fff;
      border-radius: 3px;
      box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    }

    .meal-item__img-container {
      display: inline-block;
      background: #ccc;
      width: 110px;
      height: 100px;
      float: left;
    }

    .meal-item__detail {
      float: left;
      display: inline-block;
      margin-left: 12px;
      margin-top: 5px;
    }

    .meal-item__title {
      font-size: 14px;
      font-weight: bold;
      margin-top: 10px;
    }

    .meal-item__vendor {
      font-size: 12px;
      margin-top: 5px;
    }

    .meal-item__qty {
      margin-top: 5px;
      font-size: 12px;
    }

    .balance-panel {
      background: #fff;
      border-radius: 4px;
      box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1)
    }

    .current-balance, .balance-logs {
      padding: 26px 15px;
    }

    .home-panel__heading {
      margin-top: 0;
      margin-bottom: 16px;
      font-weight: bold;
      font-size: 16px;
      color: #86919F;
    }

    .balance-logs__table {
       font-size: 12px;
       width: 100%;
    }

    .balance-logs tr td {
      padding: 4px;
    }

    .balance-logs tr td:first-child {
      padding-left: 0px;
    }

    .balance-logs tr td:last-child {
      padding-right: 0px;
      text-align: right;
    }

    .balance-added {
      color: #4CAF50;
    }

    .home-sub-title {
      margin-top: 0;
      font-weight: bold;
      font-size: 16px;
      color: #86919F;
      margin-top: 26px;
      margin-bottom: 24px;
    }

    .balance-log__action {
      text-align: center;
      margin-top: 18px;
    }
  </style>
@endpush

@section('body')
<div class="container container--small">
  <div class="row">
    <div class="col-xs-12">
      <div class="page-title-greeting" style="margin-bottom: 28px">
        <h1 class="page-title">
          Beranda
          <span style="font-size: 16px" class="pull-right">Halo {{ auth()->user()->name }}!</span>
        </h1>
      </div>
    </div>
  </div>
  <!-- <div class="row">
    <div class="col-xs-12">
      <div class="greetings" style="padding: 8px 0 12px">
        <h4></h4>
      </div>
    </div>
  </div> -->
  <div class="row">
    <div class="col-xs-12 col-sm-4">
      <h2 class="home-sub-title">Minggu Ini</h2>
      <div class="no-plan">
        <p>Ooops! Belum pesan siang minggu ini</p>
      </div>
      <div class="meal-day">
        <div class="meal-day__date">Senin, 17 Desember 2017</div>
        <div class="meal-day__items">
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/110/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/110/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Gado-gado</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
        </div>
      </div>
      <div class="meal-day">
        <div class="meal-day__date">Selasa, 18 Desember 2017</div>
        <div class="meal-day__items">
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/110/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
        </div>
      </div>
      <div class="meal-day">
        <div class="meal-day__date">Rabu, 19 Desember 2017</div>
        <div class="meal-day__items">
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/110/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
              <div class="meal-item__qty">2 pcs</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-4">
      <h2 class="home-sub-title">Minggu Depan</h2>
      <div class="no-plan">
        <p>Ooops! Belum pesan siang minggu depan</p>
        <br />
        <a href="/meals" class="btn btn--primary btn-sm">
          <i class="glyphicon glyphicon-eye-open"></i> Lihat Katalog</a>
      </div>
      <div class="meal-day">
        <div class="meal-day__date">Senin, 25 Desember 2017</div>
        <div class="meal-day__items">
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/110/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/110/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Gado-gado</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="balance-panel">
        <div class="current-balance">
          <h2 class="home-panel__heading">Balance</h2>
          <p>Jumlah balance Anda sekarang</p>
          <p style="font-size: 28px; margin: 0">Rp. {{ number_format(auth()->user()->balance()) }}</p>
        </div>
        <hr style="margin: 0">
        <div class="balance-logs">
          <h2 class="home-panel__heading">Aktivitas</h2>
          <table class="balance-logs__table">
            <tbody>
              <tr>
                <td>18/08</td>
                <td>Top-up dari perusahaan ...</td>
                <td>
                  <span class="balance-added">+ 200 poin</span>
                </td>
              </tr>
              <tr>
                <td>17/08</td>
                <td>Pembayaran order #27 ...</td>
                <td>
                  <span>- 120 poin</span>
                </td>
              </tr>
              <tr>
                <td>10/08</td>
                <td>Top-up dari perusahaan ...</td>
                <td>
                  <span class="balance-added">+ 200 poin</span>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="balance-log__action">
            <button class="btn btn--primary-outline btn-sm">Selengkapnya</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
