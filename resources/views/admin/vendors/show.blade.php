@extends('layouts.admin')

@section('title', 'Eatvora - ' . $vendor->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => $vendor->name])

        <div class="col-lg-4">
          <div class="vendor-detail">
            <div class="vendor-detail__capacity">
              <div class="vendor-detail__capacity-header">Capacity</div>
              <div>{{ $vendor->capacity }} meal / day</div>
            </div>
            @if ($vendor->address)
            <div class="vendor-detail__address">
              <div class="vendor-detail__address-header">Address</div>
              <div class="vendor-detail__address-content">{{ $vendor->address }}</div>
            </div>
            @endif
            <div class="vendor-detail__contact">
              @if ($vendor->email)
              <div class="vendor-detail__email">
                <i class="icon-envelope"></i> {{ $vendor->email }}
              </div>
              @endif
              @if ($vendor->phone)
              <div class="vendor-detail__phone">
                <i class="icon-phone"></i> {{ $vendor->phone }}
              </div>
              @endif
            </div>
            <div class="vendor-detail__action">
              <a href="#" class="text-danger">Delete</a>
              <div class="pull-right">
                <a href="{{ url("/ap/vendors/$vendor->id/edit") }}">Edit</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-offset-1 col-lg-7">
          <vendor-menu-list :vendor-id="{{ $vendor->id }}"></vendor-menu-list>
        </div>

      </div>
    </div>
  </div>

@endsection

@section('modals')
  <delete-menu-modal></delete-menu-modal>
@endsection

@push('afterScripts')
  <script type="text/javascript" src="/js/admin/admin.js"></script>
@endpush
