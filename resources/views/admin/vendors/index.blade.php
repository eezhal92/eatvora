@extends('layouts.admin')

@section('title', 'Eatvora - Vendor List')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Vendor List'])

        <div class="col-lg-12">
          <div class="action-panel">
            <a href="{{ url('/ap/vendors/create') }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Email</th>
                <th>Capacity</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($vendors as $vendor)
              <tr>
                <td>{{ $vendor->name }}</td>
                <td>{{ $vendor->phone }}</td>
                <td>{{ $vendor->address }}</td>
                <td>{{ $vendor->email }}</td>
                <td style="text-align: right">{{ $vendor->capacity }} meal / day</td>
                <td>
                  <vendor-row-action :vendor="{{ $vendor }}"></vendor-row-action>
                </td>
              </tr>
              @endforeach
          </table>
          {{ $vendors->appends(\Request::except('page'))->links() }}
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
