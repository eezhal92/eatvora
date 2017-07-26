@extends('layouts.admin')

@section('title', 'Eatvora - ' . $vendor->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => $vendor->name])

        <div class="col-lg-4">
          <div class="vendor-detail">
            <div class="vendor-detail__address">
              {{ $vendor->address }}
            </div>
            <div class="vendor-detail__phone"></div>
            <div class="vendor-detail__contact">
              <div class="vendor-detail__email">
                <i class="icon-envelope"></i> {{ $vendor->email }}
              </div>
              <div class="vendor-detail__phone">
                <i class="icon-phone"></i> {{ $vendor->phone }}
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-offset-1 col-lg-7">
          <div class="vendor-menu-list">
            <div>Menu List</div>
            <table class="table">
              <thead>
                <tr>
                  <th></th>
                  <th>Menu</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($vendor->menus as $menu)
                  <tr>
                    <td></td>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->formattedPrice() }}</td>
                    <td></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection
