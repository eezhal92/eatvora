@extends('layouts.admin')

@section('title', 'Eatvora - ' . $menu->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Menu Detail of ' . $menu->name])

        <div class="col-lg-4">
          <div style="background: #ececec">
            <img src="http://lorempixel.com/480/320/food" class="img-responsive" alt="">
          </div>
        </div>

        <div class="col-lg-offset-1 col-lg-7 info-subset">
          <div class="menu-detail__vendor info-subset">
            <div class="menu-detail__vendor__header info-subset__header">
              Vendor
            </div>
            <div class="menu-detail__vendor__content info-subset__content">
              {{ $menu->vendor->name }}
            </div>
          </div>
          <div class="menu-detail__price info-subset">
            <div class="menu-detail__price-header info-subset__header">
              Price
            </div>
            <div class="menu-detail__price-content info-subset__content">
              {{ $menu->formattedPrice() }}
            </div>
          </div>
          <div class="menu-detail__description info-subset">
            <div class="menu-detail__description-header info-subset__header">
              Description
            </div>
            <div class="menu-detail__description-content info-subset__content">
              {{ $menu->description }}
            </div>
          </div>
          <div class="menu-detail__contents info-subset">
            <div class="menu-detail__contents-header info-subset__header">
              Contents
            </div>
            <div class="menu-detail__contents-content info-subset__content">
              {{ $menu->contents }}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script type="text/javascript" src="/js/admin/admin.js"></script>
@endpush