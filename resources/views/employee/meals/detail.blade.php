@extends('layouts.employee')

@section('title' , $menu->name . ' - Eatvora')

@section('body')

  <section style="padding: 22px 0">
    <div class="container container--small">
      <div class="row">
        <div class="col-xs-12 col-sm-4">
          <div class="menu-detail__panel">
            <div class="menu-detail__img-wrapper">
              <img class="img-responsive" src="http://lorempixel.com/600/480/food" />
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-5">
          <h1 class="page-title" style="margin-top: 0">{{ $menu->name }}</h1>
          <div class="menu-detail__vendor-category">
            <span style="margin-right: 10px"><i class="fa fa-user"></i> {{ $menu->vendor->name }}</span>
            <span><i class="fa fa-cutlery"></i> Diet, Spicy</span>
          </div>

          <br>

          <div class="menu-detail__row">
            <div class="menu-detail__heading">
              Konten
            </div>
            <div class="menu-detail__content">
              {{ $menu->contents }}
            </div>
          </div>

          <div class="menu-detail__row">
            <div class="menu-detail__heading">
              Deskripsi
            </div>
            <div class="menu-detail__content">
              {{ $menu->description }}
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-3">
          <div class="menu-detail__panel">
            <div class="menu-detail__panel-body">
              <div style="color: #86919F;font-size: 16px; font-weight: bold">Harga</div>
              <div class="menu-detail__price">{{ $menu->formattedFinalPrice() }}</div>
            </div>
          </div>
          <br>
          @if ($renderAddToCartButton && $menu->nextweek_remaining_qty)
            <button class="btn btn-block btn--primary">Ingin Ini</button>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
