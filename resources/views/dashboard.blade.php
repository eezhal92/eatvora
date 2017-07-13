@extends('layouts.employee')

@push('head')
  <style>
    .meal-day {
      margin-bottom: 20px;
    }

    .meal-day__date {
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .meal-item {
      overflow: hidden;
      margin-bottom: 15px;
      background: #fff;
      border-radius: 4px;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.05);
    }

    .meal-item__img-container {
      display: inline-block;
      background: #ccc;
      width: 100px;
      height: 100px;
      float: left;
    }

    .meal-item__detail {
      float: left;
      display: inline-block;
      margin-left: 10px;
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
  </style>
@endpush

@section('body')
<div class="container container--small">
  <div class="row">
    <div class="col-xs-12">
      <h1 class="page-title">Beranda</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-4">
      <h2 class="page-sub-title">Makan Siang Minggu Ini</h2>
      <div class="meal-day">
        <div class="meal-day__date">Senin, 17 Desember 2017</div>
        <div class="meal-day__items">
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/100/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/100/100/food" class="meal-item__img" />
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
              <img src="http://lorempixel.com/100/100/food" class="meal-item__img" />
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
              <img src="http://lorempixel.com/100/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-offset-1 col-sm-4">
      <h2 class="page-sub-title">Makan Minggu Depan</h2>
      <div class="no-plan" style="text-align: center; padding: 40px; 20px; border: 1px solid #eeeeee; border-radius: 4px">
        <p>Ooops! Belum pesan siang minggu depan</p>
        <br />
        <a href="/meals" class="btn btn--primary btn-sm">Lihat Katalog</a>
      </div>
      <hr />
      <div class="meal-day">
        <div class="meal-day__date">Senin, 25 Desember 2017</div>
        <div class="meal-day__items">
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/100/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Nasi Kuning Ayam Bakar</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
          <div class="meal-item">
            <div class="meal-item__img-container">
              <img src="http://lorempixel.com/100/100/food" class="meal-item__img" />
            </div>
            <div class="meal-item__detail">
              <div class="meal-item__title">Gado-gado</div>
              <div class="meal-item__vendor">Oleh Dapur Lulu</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
