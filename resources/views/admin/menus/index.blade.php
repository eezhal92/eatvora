@extends('layouts.admin')

@section('title', 'Eatvora - Menu List')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Menu List'])

        @include('admin.message')

        <div class="col-lg-12">
          <div class="action-panel" style="margin-bottom: 15px;">
            <a href="{{ url('/ap/menus/create') }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr style="font-family: 'Open Sans Condensed', sans-serif; ">
                <th>Name</th>
                <th>Vendor</th>
                <th>Price</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($menus as $menu)
              <tr>
                <td>{{ $menu->name }}</td>
                <td>
                  <a href="{{ url('/ap/vendors/' . $menu->vendor->id) }}">{{ $menu->vendor->name }}</a>
                </td>
                <td>{{ $menu->formattedPrice() }}</td>
                <td>
                  <a href="{{ url('/ap/menus/' . $menu->id) }}">View</a>
                </td>
              </tr>
              @endforeach
          </table>
          {{ $menus->appends(\Request::except('page'))->links() }}
        </div>

      </div>
    </div>
  </div>

@endsection

@section('modals')
  <delete-vendor-modal></delete-vendor-modal>
@endsection

@push('afterScripts')
  <script src="/js/admin/admin.js"></script>
@endpush
