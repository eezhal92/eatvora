@extends('layouts.admin')

@section('title', 'Eatvora - ' . $company->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => $company->name])

        <div class="col-lg-4">
          <div class="office-list">
            <div class="office-list__header">
              {{ $company->name }} Offices
            </div>
            @foreach($company->offices as $office)
              <div class="office-list__item office">
                <div class="office__name-wrapper">
                  <h2 class="office__name">{{ $office->name }}</h2>
                  <div class="office__name-badge">Main Office</div>
                </div>
                <div class="office__address">
                  <div class="office__address-header">
                    Address
                  </div>
                  <div class="office__address-content">
                    <div class="office__address-address">
                      {{ $office->address }}
                    </div>
                    <div class="office__address-district">
                      Slipi, Palmerah
                    </div>
                    <div class="office__address-city">
                      Jakarta Barat
                    </div>
                  </div>
                </div>
                <div class="office__contact">
                  <div class="office__contact-email">
                    <i class="icon-envelope"></i> something@mail.com
                  </div>
                  <div class="office__contact-phone">
                    <div class="icon-phone"></div> +62 852 5852
                  </div>
                </div>
                <div class="office__action">
                  @if(!$office->is_main)
                  <a href="#" class="text-danger">Delete</a>
                  @endif
                  <div class="pull-right">
                    <a href="{{ $office->is_main ? url('/ap/companies/' . $company->id . '/edit') : '#' }}">Edit</a>
                    <a href="#">Show Employee</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection
