@extends('layouts.admin')

@section('title', 'Eatvora - ' . $menu->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => sprintf('Detail of %s Menu', $menu->name)])

        <div class="col-lg-4">
          <div style="background: #ececec">
            @if ($menu->image_path)
            <img src="{{ asset('storage/' . $menu->image_path) }}" class="img-responsive" style="width: 100%" alt="">
            @else
            <img src="{{ asset('images/menu-placeholder.png' )}}" class="img-responsive" alt="">
            @endif
          </div>
          <div class="text-center" style="padding: 5px 0">
            <a href="{{ url(sprintf('/ap/menus/%s/edit', $menu->id)) }}">Edit</a>
          </div>
        </div>

        <div class="col-lg-offset-1 col-lg-7 info-subset">
        <div class="menu-detail__categories info-subset">
            <div class="menu-detail__categories-header info-subset__header">
              Categories
            </div>
            <div class="menu-detail__categories-content info-subset__content">
              {{ implode(', ', $menu->categories->pluck('name')->toArray()) }}
            </div>
          </div>
          <div class="menu-detail__vendor info-subset">
            <div class="menu-detail__vendor__header info-subset__header">
              Vendor
            </div>
            <div class="menu-detail__vendor__content info-subset__content">
              <a href="{{ url(sprintf('/ap/vendors/%d', $menu->vendor->id)) }}">{{ $menu->vendor->name }}</a>
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
          <div class="menu-detail__final-price info-subset">
            <div class="menu-detail__price-header info-subset__header">
              Final Price
            </div>
            <div class="menu-detail__price-content info-subset__content">
              {{ $menu->formattedFinalPrice() }}
            </div>
          </div>
          <div class="menu-detail__description info-subset">
            <div class="menu-detail__description-header info-subset__header">
              Description
            </div>
            <div class="menu-detail__description-content info-subset__content">
              {{ $menu->description ?: '-' }}
            </div>
          </div>
          <div class="menu-detail__contents info-subset">
            <div class="menu-detail__contents-header info-subset__header">
              Contents
            </div>
            <div class="menu-detail__contents-content info-subset__content">
              {{ $menu->contents ?: '-' }}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
@endpush

